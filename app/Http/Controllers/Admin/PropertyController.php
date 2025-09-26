<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display admin properties list with featured toggle functionality
     */
    public function index(Request $request)
    {
        $query = Property::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('mls_number', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Featured filter
        if ($request->filled('featured')) {
            $featured = $request->get('featured');
            if ($featured === 'yes') {
                $query->where('featured', true);
            } elseif ($featured === 'no') {
                $query->where('featured', false);
            }
        }

        $properties = $query->withCount('images')
                          ->orderByDesc('featured')
                          ->orderByDesc('created_at')
                          ->paginate(20)
                          ->appends($request->all());

        return view('admin.properties.index', compact('properties'));
    }

    /**
     * Toggle the featured status of a property
     */
    public function toggleFeatured(Property $property)
    {
        $property->update([
            'featured' => !$property->featured
        ]);

        $status = $property->featured ? 'featured' : 'unfeatured';
        
        return response()->json([
            'success' => true,
            'featured' => $property->featured,
            'message' => "Property {$status} successfully!"
        ]);
    }
}
