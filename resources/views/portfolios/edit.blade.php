@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <!-- Help Instructions -->
                <div class="bg-blue-50 border-l-4 border-blue-500 px-4 py-3 rounded mb-6">
                    <p class="text-sm text-blue-900">
                        <strong>KV :</strong> Information.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-4">
                        <p class="font-bold mb-1">Please fix these errors:</p>
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded mb-4">
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                @endif

        <form action="{{ route('portfolio.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

                <!-- Quick Save Button at Top -->
                <div class="mb-6 p-4 bg-blue-600 rounded text-right">
                    <button type="submit" class="px-6 py-2 bg-white text-blue-600 font-bold rounded hover:bg-gray-100">
                        SAVE PORTFOLIO
                    </button>
                </div>

                <!-- Personal Information -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-700 border-b pb-2">Personal Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $portfolio->full_name) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $portfolio->title) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $portfolio->email) }}" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $portfolio->phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location', $portfolio->location) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-1">Profile Image</label>
                        <input type="file" name="profile_image" id="profile_image" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @if ($portfolio->profile_image)
                            <p class="text-sm text-gray-500 mt-1">Current: {{ basename($portfolio->profile_image) }}</p>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">About Me Description</label>
                    <textarea name="bio" id="bio" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Passionate full-stack developer with expertise in modern web technologies...">{{ old('bio', $portfolio->bio) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">This appears at the top of the About Me section</p>
                </div>
            </div>

                <!-- Hero Section -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-700 border-b pb-2">Home Section</h2>
                
                <div class="mb-4">
                    <label for="greeting" class="block text-sm font-medium text-gray-700 mb-1">Greeting</label>
                    <input type="text" name="greeting" id="greeting" value="{{ old('greeting', $portfolio->greeting) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>


                <!-- My Journey Section -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-700 border-b pb-2">My Journey</h2>
                
                <div>
                    <label for="my_journey" class="block text-sm font-medium text-gray-700 mb-1">My Journey Story</label>
                    <textarea name="my_journey" id="my_journey" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Share your professional journey, experiences, and how you got to where you are today...">{{ old('my_journey', $portfolio->my_journey) }}</textarea>
                </div>
            </div>

                <!-- About Stats -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-700 border-b pb-2">Statistics</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="projects_completed" class="block text-sm font-medium text-gray-700 mb-1">Projects Completed</label>
                        <input type="number" name="projects_completed" id="projects_completed" value="{{ old('projects_completed', $portfolio->projects_completed) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="years_experience" class="block text-sm font-medium text-gray-700 mb-1">Years Experience</label>
                        <input type="number" name="years_experience" id="years_experience" value="{{ old('years_experience', $portfolio->years_experience) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="happy_clients" class="block text-sm font-medium text-gray-700 mb-1">Happy Clients</label>
                        <input type="number" name="happy_clients" id="happy_clients" value="{{ old('happy_clients', $portfolio->happy_clients) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="satisfaction_rate" class="block text-sm font-medium text-gray-700 mb-1">Satisfaction Rate (%)</label>
                        <input type="number" name="satisfaction_rate" id="satisfaction_rate" value="{{ old('satisfaction_rate', $portfolio->satisfaction_rate) }}" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

                <!-- Skills -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-700 border-b pb-2">Skills</h2>
                <p class="text-sm text-gray-600 mb-4">Enter skills in JSON format: [{"name": "HTML", "percentage": 92}, ...]</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="frontend_skills" class="block text-sm font-medium text-gray-700 mb-1">Frontend Skills</label>
                        <textarea name="frontend_skills" id="frontend_skills" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm">{{ old('frontend_skills', json_encode($portfolio->frontend_skills, JSON_PRETTY_PRINT)) }}</textarea>
                    </div>

                    <div>
                        <label for="backend_skills" class="block text-sm font-medium text-gray-700 mb-1">Backend Skills</label>
                        <textarea name="backend_skills" id="backend_skills" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm">{{ old('backend_skills', json_encode($portfolio->backend_skills, JSON_PRETTY_PRINT)) }}</textarea>
                    </div>

                    <div>
                        <label for="tools_skills" class="block text-sm font-medium text-gray-700 mb-1">Tools</label>
                        <textarea name="tools_skills" id="tools_skills" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm">{{ old('tools_skills', json_encode($portfolio->tools_skills, JSON_PRETTY_PRINT)) }}</textarea>
                    </div>

                    <div>
                        <label for="technologies" class="block text-sm font-medium text-gray-700 mb-1">Technologies</label>
                        <textarea name="technologies" id="technologies" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm">{{ old('technologies', json_encode($portfolio->technologies, JSON_PRETTY_PRINT)) }}</textarea>
                    </div>
                </div>
            </div>

                <!-- Education -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-700 border-b pb-2">Education</h2>
                <p class="text-sm text-gray-600 mb-4">Enter education in JSON format: [{"school": "School Name", "degree": "Degree", "year": "2020"}]</p>
                
                <textarea name="education" id="education" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm">{{ old('education', json_encode($portfolio->education, JSON_PRETTY_PRINT)) }}</textarea>
            </div>

                <!-- Featured Projects -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-2 text-gray-700 border-b pb-2">Featured Projects</h2>
                    <p class="text-sm text-gray-600 mb-4">Add up to 6 projects to showcase your work.</p>
                
                <div id="projects-container">
                    @php
                        $existingProjects = old('projects', $portfolio->projects ?? []);
                        if (is_string($existingProjects)) {
                            $existingProjects = json_decode($existingProjects, true) ?? [];
                        }
                        $projectCount = max(count($existingProjects), 1);
                    @endphp
                    
                    @for ($i = 0; $i < $projectCount; $i++)
                        @php
                            $project = $existingProjects[$i] ?? ['title' => '', 'description' => '', 'type' => '', 'image' => ''];
                            // Handle old 'category' field - migrate to 'type'
                            if (!isset($project['type']) && isset($project['category'])) {
                                $project['type'] = $project['category'];
                            }
                            if (!isset($project['type'])) {
                                $project['type'] = '';
                            }
                        @endphp
                        <div class="project-item mb-4 p-4 border border-gray-300 rounded bg-gray-50">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-lg font-bold text-gray-700">Project {{ $i + 1 }}</h3>
                                @if ($i > 0)
                                    <button type="button" class="remove-project px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">
                                        Remove
                                    </button>
                                @endif
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Project Title *</label>
                                    <input type="text" name="projects[{{ $i }}][title]" value="{{ $project['title'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="E.g., E-Commerce Website" required>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Project Type *</label>
                                    <input type="text" name="projects[{{ $i }}][type]" value="{{ $project['type'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="E.g., Web Development, Mobile App, Design" required>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                                    <textarea name="projects[{{ $i }}][description]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Describe what the project does, technologies used, and your role..." required>{{ $project['description'] ?? '' }}</textarea>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Project Image (optional)</label>
                                    <input type="hidden" name="projects[{{ $i }}][image]" value="{{ $project['image'] ?? '' }}" class="existing-image-field">
                                    <div class="flex items-center gap-4">
                                        <input type="file" name="project_images[{{ $i }}]" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        @if(!empty($project['image']))
                                            <span class="text-sm text-gray-600">Current: {{ $project['image'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
                    
                    <button type="button" id="add-project" class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Add Another Project
                    </button>
                </div>

            <script>
                let projectIndex = {{ $projectCount }};
                
                document.getElementById('add-project').addEventListener('click', function() {
                    if (projectIndex >= 6) {
                        alert('Maximum 6 projects allowed');
                        return;
                    }
                    
                    const container = document.getElementById('projects-container');
                    const newProject = `
                        <div class="project-item mb-4 p-4 border border-gray-300 rounded bg-gray-50">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-lg font-bold text-gray-700">Project ${projectIndex + 1}</h3>
                                <button type="button" class="remove-project px-3 py-1 bg-red-600 text-white rounded text-sm hover:bg-red-700">
                                    Remove
                                </button>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Project Title *</label>
                                    <input type="text" name="projects[${projectIndex}][title]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="E.g., E-Commerce Website" required>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Project Type *</label>
                                    <input type="text" name="projects[${projectIndex}][type]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="E.g., Web Development, Mobile App, Design" required>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                                    <textarea name="projects[${projectIndex}][description]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Describe what the project does, technologies used, and your role..." required></textarea>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Project Image (optional)</label>
                                    <input type="hidden" name="projects[${projectIndex}][image]" value="" class="existing-image-field">
                                    <div class="flex items-center gap-4">
                                        <input type="file" name="project_images[${projectIndex}]" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    container.insertAdjacentHTML('beforeend', newProject);
                    projectIndex++;
                    
                    attachRemoveHandlers();
                });
                
                function attachRemoveHandlers() {
                    document.querySelectorAll('.remove-project').forEach(button => {
                        button.onclick = function() {
                            this.closest('.project-item').remove();
                        };
                    });
                }
                
                attachRemoveHandlers();
            </script>

                <!-- Social Links -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-700 border-b pb-2">Social Links</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="github_url" class="block text-sm font-medium text-gray-700 mb-1">GitHub URL</label>
                        <input type="url" name="github_url" id="github_url" value="{{ old('github_url', $portfolio->github_url) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="linkedin_url" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn URL</label>
                        <input type="url" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', $portfolio->linkedin_url) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="twitter_url" class="block text-sm font-medium text-gray-700 mb-1">Twitter URL</label>
                        <input type="url" name="twitter_url" id="twitter_url" value="{{ old('twitter_url', $portfolio->twitter_url) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-between items-center gap-3 mt-8 pt-6 border-t border-gray-300">
                    <a href="{{ route('home') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        View
                    </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded hover:bg-blue-700">
                            SAVE PORTFOLIO
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
