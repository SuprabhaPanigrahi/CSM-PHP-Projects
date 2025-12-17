<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseController extends Controller
{
    // Sample courses data
    private $courses = [
        [
            'id' => 1,
            'title' => 'Web Development',
            'slug' => 'web-development',
            'category' => 'programming',
            'duration' => '12 weeks',
            'level' => 'Beginner',
            'description' => 'Learn HTML, CSS, JavaScript, and PHP to build modern websites.'
        ],
        [
            'id' => 2,
            'title' => 'Data Science',
            'slug' => 'data-science',
            'category' => 'data',
            'duration' => '16 weeks',
            'level' => 'Intermediate',
            'description' => 'Master Python, statistics, and machine learning algorithms.'
        ],
        [
            'id' => 3,
            'title' => 'Digital Marketing',
            'slug' => 'digital-marketing',
            'category' => 'business',
            'duration' => '8 weeks',
            'level' => 'Beginner',
            'description' => 'Learn SEO, social media marketing, and content strategy.'
        ],
        [
            'id' => 4,
            'title' => 'Graphic Design',
            'slug' => 'graphic-design',
            'category' => 'design',
            'duration' => '10 weeks',
            'level' => 'Beginner',
            'description' => 'Master Adobe Photoshop, Illustrator, and design principles.'
        ]
    ];

    public function index(Request $request)
    {
        // Get category from query parameter
        $category = $request->query('category');
        
        // Filter courses if category is provided
        if ($category) {
            $filteredCourses = array_filter($this->courses, function($course) use ($category) {
                return $course['category'] === $category;
            });
            $courses = array_values($filteredCourses); // Reset array keys
        } else {
            $courses = $this->courses;
        }

        // Check if client wants JSON
        if ($request->wantsJson()) {
            return response()->json([
                'courses' => $courses,
                'category' => $category
            ], 200);
        }

        // Return view for web browsers
        return view('courses.index', [
            'courses' => $courses,
            'selectedCategory' => $category
        ]);
    }

    public function show($slug)
    {
        // Find course by slug
        $course = null;
        foreach ($this->courses as $c) {
            if ($c['slug'] === $slug) {
                $course = $c;
                break;
            }
        }

        // If course not found, show 404
        if (!$course) {
            abort(404);
        }

        return view('courses.show', ['course' => $course]);
    }
}










    $loader = \Illuminate\Foundation\AliasLoader::getInstance();
    $loader->alias('Debugbar', \Barryvdh\Debugbar\Facades\Debugbar::class);