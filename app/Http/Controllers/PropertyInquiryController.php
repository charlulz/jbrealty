<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyInquiry;
use Illuminate\Http\Request;

class PropertyInquiryController extends Controller
{
    /**
     * Store a new property inquiry.
     */
    public function store(Request $request, Property $property)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'inquiry_type' => 'required|in:general,showing,offer,financing,information',
            'message' => 'required|string|max:2000',
            'max_budget' => 'nullable|numeric|min:0',
            'needs_financing' => 'nullable|boolean',
            'additional_info' => 'nullable|string|max:1000',
        ]);

        // Add property and tracking info
        $validated['property_id'] = $property->id;
        $validated['ip_address'] = $request->ip();
        $validated['user_agent'] = $request->userAgent();
        $validated['referral_source'] = $request->header('referer');

        // Assign to listing agent if available
        if ($property->listing_agent_id) {
            $validated['assigned_to'] = $property->listing_agent_id;
        }

        $inquiry = PropertyInquiry::create($validated);

        // Increment property inquiry count
        $property->incrementInquiries();

        return redirect()->back()->with('success', 'Thank you for your inquiry! We\'ll be in touch soon.');
    }

    /**
     * Display inquiries for admin.
     */
    public function index(Request $request)
    {
        $inquiries = PropertyInquiry::with(['property', 'assignedAgent'])
            ->when($request->status, function($query, $status) {
                $query->byStatus($status);
            })
            ->when($request->type, function($query, $type) {
                $query->byType($type);
            })
            ->recent()
            ->paginate(20);

        return view('admin.inquiries.index', compact('inquiries'));
    }

    /**
     * Display a specific inquiry.
     */
    public function show(PropertyInquiry $inquiry)
    {
        $inquiry->load(['property', 'assignedAgent']);

        return view('admin.inquiries.show', compact('inquiry'));
    }

    /**
     * Update inquiry status.
     */
    public function update(Request $request, PropertyInquiry $inquiry)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,qualified,converted,closed',
            'agent_notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validated['status'] === 'contacted' && !$inquiry->contacted_at) {
            $validated['contacted_at'] = now();
        }

        $inquiry->update($validated);

        return redirect()->back()->with('success', 'Inquiry updated successfully!');
    }
}
