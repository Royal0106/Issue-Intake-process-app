<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issue;
use App\Models\Category;
use Illuminate\Support\Facades\Http;
use OpenAI;

class IssueController extends Controller
{
    public function index(Request $request)
    {
        $query = Issue::query()->with('category');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $issues = $query->get();
        $categories = Category::all();

        return view('issues.index', compact('issues', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('issues.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Step 1: Validate input
         $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'category_id' => 'required|exists:categories,id',
            'status' => 'sometimes|in:Open,In Progress,Closed',
        ]);

        // Step 2: Initialize OpenAI client (correct way)
        $client = OpenAI::client(env('OPENAI_API_KEY'));

        // Step 3: Attempt AI generation
        [$summary, $nextAction] = [null, null]; // default

        try {
            $prompt = "
                        You are a support assistant.
                        Summarize this issue in 1-2 sentences and suggest the next action.
                        Respond in JSON: {\"summary\": \"...\", \"next_action\": \"...\"}
                        Issue: {$data['description']}
                    ";

            $response = $client->chat()->create([
                'model' => 'gpt-4o-mini', // or 'gpt-3.5-turbo'
                'messages' => [
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.2
            ]);

            $text = $response->choices[0]->message->content;

            $parsed = json_decode($text, true);
            if ($parsed && isset($parsed['summary'], $parsed['next_action'])) {
                $summary = $parsed['summary'];
                $nextAction = $parsed['next_action'];
            }
        } catch (\Exception $e) {
            \Log::error("AI generation failed: ".$e->getMessage());
        }

        // Step 4: Rules-based fallback if AI fails

        [$summary, $nextAction] = $this->rulesBasedSummaryAndAction($data);
        // Step 5: Save issue with AI/rules-generated fields
        $data['summary'] = $summary;
        $data['next_action'] = $nextAction;

        $issue = Issue::create($data);

        return redirect()->route('index');
    }
        
    public function show($id)
    {
        $issue = Issue::findOrFail($id);
        return view('issues.show', compact('issue'));
    }

    public function edit($id)
    {
        $issue = Issue::findOrFail($id);
        $categories = Category::all();
        return view('issues.edit', compact('issue', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $issue = Issue::findOrFail($id);
        $issue->update($request->all());
        return redirect()->route('show', $issue->id);
    }

    public function destroy($id)
    {
        $issue = Issue::findOrFail($id);
        $issue->delete();
        return redirect()->route('index');
    }

    private function rulesBasedSummaryAndAction(array $issue): array
    {
        $description = trim($issue['description']);
        $priority = strtolower($issue['priority'] ?? 'medium');
        $category_id = $issue['category_id'] ?? null;

        // Generate summary: first 2-3 sentences
        $sentences = preg_split('/(?<=[.?!])\s+/', $description);
        $summary = implode(' ', array_slice($sentences, 0, 3));

        // Determine category name if available
        $categoryName = null;
        if (isset($issue['category'])) {
            $categoryName = strtolower($issue['category']);
        }

        // Initialize next action
        $nextAction = "Review issue and assign appropriately.";

        // Keyword escalation
        $keywordsEscalate = ['urgent', 'immediately', 'outage', 'crash', 'failure'];
        foreach ($keywordsEscalate as $word) {
            if (stripos($description, $word) !== false) {
                $nextAction = "Escalate immediately due to urgent keywords.";
                return [$summary, $nextAction];
            }
        }

        // Priority-based rules
        if ($priority === 'high') {
            $nextAction = "Escalate to manager immediately.";
        } elseif ($priority === 'medium') {
            $nextAction = "Assign to responsible team for resolution.";
        } elseif ($priority === 'low') {
            $nextAction = "Review and assign at normal priority.";
        }

        // Category-based rules override
        if ($categoryName) {
            if (str_contains($categoryName, 'bug')) {
                $nextAction = "Assign to engineering team.";
            } elseif (str_contains($categoryName, 'feature')) {
                $nextAction = "Assign to product manager.";
            } elseif (str_contains($categoryName, 'incident') || str_contains($categoryName, 'support')) {
                $nextAction = "Assign to support team.";
            }
        }

        // Ensure non-empty values
        if (empty($summary)) {
            $summary = substr($description, 0, 100) . (strlen($description) > 100 ? '...' : '');
        }
        if (empty($nextAction)) {
            $nextAction = "Review issue and assign appropriately.";
        }

        return [$summary, $nextAction];
    }
}
