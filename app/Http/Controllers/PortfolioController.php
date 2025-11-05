<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PortfolioController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of portfolios (admin only).
     */
    public function index()
    {
        $this->authorize('viewAny', Portfolio::class);
        $portfolios = Portfolio::with('user')->latest()->paginate(10);
        return view('portfolios.index', compact('portfolios'));
    }

    /**
     * Show the form for editing the authenticated user's portfolio.
     */
    public function edit()
    {
        $user = Auth::user();
        $portfolio = $user->portfolio;

        // Create default portfolio if doesn't exist
        if (!$portfolio) {
            $this->authorize('create', Portfolio::class);
            $portfolio = Portfolio::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
                'email' => $user->email,
                'title' => 'Full Stack Developer',
                'location' => 'San Pascual, Batangas',
                'greeting' => 'Hello, I\'m',
                'hero_description' => 'I create modern, responsive web applications with cutting-edge technologies.',
                'about_content' => 'Passionate developer with expertise in creating modern web applications. I specialize in full-stack development and love solving complex problems with elegant solutions.',
                'my_journey' => 'My journey in web development started during my college years at Batangas State University. What began as curiosity about how websites work has evolved into a deep passion for creating impactful digital experiences. I continuously learn new technologies and enjoy bringing ideas to life through code.',
                'frontend_skills' => [
                    ['name' => 'CSS', 'progress' => 87],
                    ['name' => 'JavaScript', 'progress' => 79],
                    ['name' => 'HTML5', 'progress' => 89],
                ],
                'backend_skills' => [
                    ['name' => 'Java', 'progress' => 83],
                    ['name' => 'Python', 'progress' => 90],
                    ['name' => 'PHP', 'progress' => 85],
                    ['name' => 'PostgreSQL', 'progress' => 82],
                ],
                'tools_skills' => [
                    ['name' => 'Git/GitHub', 'progress' => 92],
                    ['name' => 'Figma', 'progress' => 85],
                    ['name' => 'VS Code', 'progress' => 95],
                ],
                'technologies' => ['PHP', 'C++', 'C#', 'MySQL', 'Java', 'PostgreSQL', 'CSS', 'JavaScript', 'Git', 'Figma', 'Photoshop', 'Python'],
                'education' => [
                    [
                        'level' => 'Elementary',
                        'school' => 'Sambat Elementary School',
                        'location' => 'Sambat, Batangas',
                        'years' => '2010 - 2017',
                        'gpa' => 'GPA: 3.9/4.0'
                    ],
                    [
                        'level' => 'Secondary',
                        'school' => 'Bauan Technical Integrated High School',
                        'location' => 'Bauan, Batangas',
                        'years' => '2017 - 2023',
                        'gpa' => 'GPA: 3.8/4.0'
                    ],
                    [
                        'level' => 'Tertiary',
                        'school' => 'Batangas State University - TNEU',
                        'location' => 'Alangilan, Batangas',
                        'years' => '2023 - Present',
                        'gpa' => 'On going'
                    ],
                ],
                'projects' => [
                    [
                        'title' => 'Banking App',
                        'category' => 'terminal',
                        'description' => 'Secure mobile banking application with biometric authentication.',
                        'image' => 'bank.png',
                        'technologies' => ['Python', 'MySQL']
                    ],
                    [
                        'title' => 'Portfolio',
                        'category' => 'website',
                        'description' => 'Modern portfolio website showcasing projects and skills.',
                        'image' => 'port.png',
                        'technologies' => ['HTML5', 'CSS3', 'JavaScript', 'PHP', 'PostgreSQL']
                    ],
                ],
            ]);
        }

        return view('portfolios.edit', compact('portfolio'));
    }

    /**
     * Update the authenticated user's portfolio.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $portfolio = $user->portfolio;
        
        $this->authorize('update', $portfolio);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'location' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'profile_image' => 'nullable|image|max:2048',
            'greeting' => 'required|string|max:255',
            'hero_description' => 'nullable|string',
            'about_content' => 'nullable|string',
            'my_journey' => 'nullable|string',
            'projects_completed' => 'required|integer|min:0',
            'years_experience' => 'required|integer|min:0',
            'happy_clients' => 'required|integer|min:0',
            'satisfaction_rate' => 'required|integer|min:0|max:100',
            'projects' => 'nullable|array',
            'projects.*.title' => 'nullable|string|max:255',
            'projects.*.type' => 'nullable|string|max:100',
            'projects.*.description' => 'nullable|string',
            'projects.*.image' => 'nullable|string|max:255',
            'project_images' => 'nullable|array',
            'project_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'github_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'is_public' => 'boolean',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($portfolio->profile_image) {
                Storage::disk('public')->delete($portfolio->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        // Handle JSON fields from the form
        $validated['frontend_skills'] = $this->parseSkills($request->input('frontend_skills'));
        $validated['backend_skills'] = $this->parseSkills($request->input('backend_skills'));
        $validated['tools_skills'] = $this->parseSkills($request->input('tools_skills'));
        $validated['technologies'] = json_decode($request->input('technologies', '[]'), true) ?? [];
        $validated['education'] = json_decode($request->input('education', '[]'), true) ?? [];
        
        // Handle projects array with image uploads
        if ($request->has('projects')) {
            $projects = $request->input('projects', []);
            
            // Handle project image uploads
            if ($request->hasFile('project_images')) {
                foreach ($request->file('project_images') as $index => $image) {
                    if ($image && $image->isValid()) {
                        // Store the image
                        $path = $image->store('projects', 'public');
                        // Update the project image path
                        if (isset($projects[$index])) {
                            $projects[$index]['image'] = basename($path);
                        }
                    }
                }
            }
            
            // Filter out empty projects
            $projects = array_filter($projects, function($project) {
                return !empty($project['title']) || !empty($project['description']);
            });
            
            $validated['projects'] = array_values($projects); // Re-index array
        } else {
            $validated['projects'] = [];
        }

        $portfolio->update($validated);

        return redirect()->route('portfolio.edit')->with('success', 'Portfolio updated successfully!');
    }

    /**
     * Display the public portfolio view (uses $_GET user_id parameter).
     */
    public function publicView(Request $request)
    {
        // Get user_id from $_GET parameter
        $userId = $request->query('user_id') ?? $_GET['user_id'] ?? null;

        if (!$userId) {
            // If no user_id, show the first public portfolio or authenticated user's portfolio
            if (Auth::check()) {
                $portfolio = Auth::user()->portfolio;
            } else {
                $portfolio = Portfolio::where('is_public', true)->first();
            }
        } else {
            $portfolio = Portfolio::where('user_id', $userId)
                ->where('is_public', true)
                ->firstOrFail();
        }

        // If no portfolio found, create a default one for demo purposes
        if (!$portfolio) {
            $portfolio = new Portfolio([
                'full_name' => 'Kenneth Valdez',
                'title' => 'Full Stack Developer',
                'email' => 'contact@example.com',
                'location' => 'San Pascual, Batangas',
                'greeting' => "Hello, I'm",
                'hero_description' => 'I create modern, responsive web applications with cutting-edge technologies.',
                'bio' => 'Passionate full-stack developer with expertise in modern web technologies.',
                'projects_completed' => 50,
                'years_experience' => 3,
                'happy_clients' => 20,
                'satisfaction_rate' => 100,
                'frontend_skills' => [
                    ['name' => 'CSS', 'progress' => 87],
                    ['name' => 'JavaScript', 'progress' => 79],
                    ['name' => 'HTML5', 'progress' => 89],
                ],
                'backend_skills' => [
                    ['name' => 'Java', 'progress' => 83],
                    ['name' => 'Python', 'progress' => 90],
                    ['name' => 'PHP', 'progress' => 85],
                    ['name' => 'PostgreSQL', 'progress' => 82],
                ],
                'tools_skills' => [
                    ['name' => 'Git/GitHub', 'progress' => 92],
                    ['name' => 'Figma', 'progress' => 85],
                    ['name' => 'VS Code', 'progress' => 95],
                ],
                'technologies' => ['PHP', 'C++', 'C#', 'MySQL', 'Java', 'PostgreSQL', 'CSS', 'JavaScript', 'Git', 'Figma', 'Photoshop', 'Python'],
                'education' => [
                    [
                        'level' => 'Elementary',
                        'school' => 'Sambat Elementary School',
                        'location' => 'Sambat, Batangas',
                        'years' => '2010 - 2017',
                        'gpa' => 'GPA: 3.9/4.0'
                    ],
                    [
                        'level' => 'Secondary',
                        'school' => 'Bauan Technical Integrated High School',
                        'location' => 'Bauan, Batangas',
                        'years' => '2017 - 2023',
                        'gpa' => 'GPA: 3.8/4.0'
                    ],
                    [
                        'level' => 'Tertiary',
                        'school' => 'Batangas State University - TNEU',
                        'location' => 'Alangilan, Batangas',
                        'years' => '2023 - Present',
                        'gpa' => 'On going'
                    ],
                ],
                'projects' => [
                    [
                        'title' => 'Banking App',
                        'category' => 'terminal',
                        'description' => 'Secure mobile banking application with biometric authentication.',
                        'image' => 'bank.png',
                        'technologies' => ['Python', 'MySQL']
                    ],
                    [
                        'title' => 'Portfolio',
                        'category' => 'website',
                        'description' => 'Modern portfolio website showcasing projects and skills.',
                        'image' => 'port.png',
                        'technologies' => ['HTML5', 'CSS3', 'JavaScript', 'PHP', 'PostgreSQL']
                    ],
                ],
            ]);
        }

        return view('portfolio', compact('portfolio'));
    }

    /**
     * Parse skills from JSON string.
     */
    private function parseSkills($jsonString)
    {
        $skills = json_decode($jsonString, true);
        return is_array($skills) ? $skills : [];
    }
}
