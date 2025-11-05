<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Portfolio' }}</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Javascript --> 
    <script src="{{ asset('js/script.js') }}"></script>
</head>
<body data-authenticated="{{ auth()->check() ? 'true' : 'false' }}">
    {{-- Reused the original static HTML content but assets now load from public/ --}}
    <!-- Header -->
    <header class="header" id="header">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo" onclick="scrollToSection('hero')">
                    KV
                </div>
                
                <nav class="nav desktop-nav">
                    <a href="#hero" onclick="scrollToSection('hero')">Home</a>
                    <a href="#about" onclick="scrollToSection('about')">About</a>
                    <a href="#education" onclick="scrollToSection('education')">Education</a>
                    <a href="#skills" onclick="scrollToSection('skills')">Skills</a>
                    <a href="#projects" onclick="scrollToSection('projects')">Projects</a>
                    <a href="#contact" onclick="scrollToSection('contact')">Contact</a>
                </nav>
                
                <div class="nav-controls">
                    <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
                        <i class="fas fa-moon"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="hero">
        <div class="hero-bg"></div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <p class="hero-greeting">{{ $portfolio->greeting ?? "Hello, I'm" }}</p>
                    <h1 class="hero-name">{{ $portfolio->full_name }}</h1>
                    <div class="hero-title">
                        <span id="typewriter">{{ $portfolio->title }}</span>
                        <span class="cursor" id="cursor">|</span>
                    </div>
                    <p class="hero-description">
                        {{ $portfolio->hero_description ?? 'I create modern, responsive web applications with cutting-edge technologies. Passionate about clean code, user experience, and innovative solutions.' }}
                    </p>
                    <div class="hero-buttons">
                        <button class="btn btn-primary" onclick="scrollToSection('projects')">
                            View My Work
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        <button class="btn btn-outline">
                            Download CV
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                    <div class="social-links">
                        @if($portfolio->github_url)
                        <a href="{{ $portfolio->github_url }}" class="social-link" aria-label="GitHub" target="_blank">
                            <i class="fab fa-github"></i>
                        </a>
                        @endif
                        @if($portfolio->linkedin_url)
                        <a href="{{ $portfolio->linkedin_url }}" class="social-link" aria-label="LinkedIn" target="_blank">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        @endif
                        @if($portfolio->email)
                        <a href="mailto:{{ $portfolio->email }}" class="social-link" aria-label="Email">
                            <i class="fas fa-envelope"></i>
                        </a>
                        @endif
                        @if($portfolio->twitter_url)
                        <a href="{{ $portfolio->twitter_url }}" class="social-link" aria-label="Twitter" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                        @endif
                    </div>
                </div>
                <div class="hero-image">
                    <div class="image-container">
                        <img src="{{ $portfolio->profile_image ? asset('storage/' . $portfolio->profile_image) : asset('images/prof.png') }}" alt="{{ $portfolio->full_name }}" class="profile-image">
                        <div class="floating-element element-1">⚡</div>
                        <div class="floating-element element-2">🚀</div>
                    </div>
                </div>
            </div>
            <div class="scroll-indicator" onclick="scrollToSection('about')">
                <span>Scroll Down</span>
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about section" id="about">
        <div class="container">
            <div class="section-header">
                <h2>About Me</h2>
                <p>{{ $portfolio->bio ?? 'Passionate full-stack developer with expertise in modern web technologies. I love turning complex problems into simple, beautiful, and intuitive solutions.' }}</p>
            </div>
            
            <div class="about-content">
                <div class="about-text">
                    @if($portfolio->my_journey)
                        <h3>My Journey</h3>
                        <div class="text-content">
                            {!! nl2br(e($portfolio->my_journey)) !!}
                        </div>
                    @else
                        <h3>My Journey</h3>
                        <div class="text-content">
                            <p>My journey into web development started during my computer science studies, where I discovered my passion for creating digital experiences that make a difference. What began as curiosity about how websites work evolved into a deep love for crafting elegant solutions to complex problems.</p>
                            <p>Over the past few years, I've had the privilege of working with startups and established companies, helping them bring their digital visions to life. I specialize in modern web technologies, but I'm always eager to learn and adapt to new challenges.</p>
                            <p>When I'm not coding, you can find me exploring new technologies, contributing to open-source projects, or sharing knowledge with the developer community. I believe in the power of collaboration and continuous learning.</p>
                        </div>
                    @endif
                </div>
                
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <h4>Clean Code</h4>
                        <p>Writing maintainable, scalable, and efficient code following best practices.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-palette"></i>
                        </div>
                        <h4>UI/UX Design</h4>
                        <p>Creating beautiful, intuitive interfaces that provide excellent user experience.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h4>Performance</h4>
                        <p>Optimizing applications for speed, accessibility, and cross-platform compatibility.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4>Collaboration</h4>
                        <p>Working effectively in teams using agile methodologies and modern tools.</p>
                    </div>
                </div>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number" data-target="{{ $portfolio->projects_completed ?? 50 }}">0</div>
                    <div class="stat-label">Projects Completed</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" data-target="{{ $portfolio->years_experience ?? 3 }}">0</div>
                    <div class="stat-label">Years Experience</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" data-target="{{ $portfolio->happy_clients ?? 20 }}">0</div>
                    <div class="stat-label">Happy Clients</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number" data-target="{{ $portfolio->satisfaction_rate ?? 100 }}">0</div>
                    <div class="stat-label">Satisfaction Rate</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Education Section -->
    <section class="education section" id="education">
        <div class="container">
            <div class="section-header">
                <h2>Educational Background</h2>
                <p>My academic journey has provided a strong foundation in computer science , combined with practical experience in modern technologies.</p>
            </div>
            
            <div class="education-timeline">
                <!-- Timeline Line -->
                <div class="timeline-line" id="timelineLine"></div>
                
                <!-- Education Items -->
                @foreach($portfolio->education ?? [] as $index => $edu)
                <div class="education-item" data-index="{{ $index }}">
                    <div class="timeline-dot" data-color="red">
                        <i class="fas fa-{{ $index === count($portfolio->education ?? []) - 1 ? 'book' : 'graduation-cap' }}"></i>
                    </div>
                    <div class="education-card">
                        <div class="education-header">
                            <div class="education-main-info">
                                <h3>{{ $edu['level'] ?? 'Education' }}</h3>
                                <div class="school-info">
                                    <i class="fas fa-university"></i>
                                    <span>{{ $edu['school'] }}</span>
                                </div>
                                @if(isset($edu['location']))
                                <p class="location">{{ $edu['location'] }}</p>
                                @endif
                            </div>
                            <div class="education-meta">
                                @if(isset($edu['years']))
                                <div class="year-badge">
                                    <i class="fas fa-calendar"></i>
                                    {{ $edu['years'] }}
                                </div>
                                @endif
                                @if(isset($edu['gpa']))
                                <div class="gpa-badge">{{ $edu['gpa'] }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section class="skills section" id="skills">
        <div class="container">
            <div class="section-header">
                <h2>Skills & Expertise</h2>
                <p>A comprehensive overview of my technical skills and the tools I use to bring ideas to life.</p>
            </div>
            
            <div class="skills-categories">
                @if(!empty($portfolio->frontend_skills))
                <div class="skill-category">
                    <h3>Frontend Development</h3>
                    <div class="skill-items">
                        @foreach($portfolio->frontend_skills as $skill)
                        <div class="skill-item">
                            <div class="skill-info">
                                <span>{{ $skill['name'] }}</span>
                                <span>{{ $skill['progress'] ?? $skill['percentage'] ?? 0 }}%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-progress="{{ $skill['progress'] ?? $skill['percentage'] ?? 0 }}"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                @if(!empty($portfolio->backend_skills))
                <div class="skill-category">
                    <h3>Backend Development</h3>
                    <div class="skill-items">
                        @foreach($portfolio->backend_skills as $skill)
                        <div class="skill-item">
                            <div class="skill-info">
                                <span>{{ $skill['name'] }}</span>
                                <span>{{ $skill['progress'] ?? $skill['percentage'] ?? 0 }}%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-progress="{{ $skill['progress'] ?? $skill['percentage'] ?? 0 }}"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                @if(!empty($portfolio->tools_skills))
                <div class="skill-category">
                    <h3>Tools & Technologies</h3>
                    <div class="skill-items">
                        @foreach($portfolio->tools_skills as $skill)
                        <div class="skill-item">
                            <div class="skill-info">
                                <span>{{ $skill['name'] }}</span>
                                <span>{{ $skill['progress'] ?? $skill['percentage'] ?? 0 }}%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-progress="{{ $skill['progress'] ?? $skill['percentage'] ?? 0 }}"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <div class="skill-info">
                                <span>VS Code</span>
                                <span>95%</span>
                            </div>
                            <div class="skill-bar">
                                <div class="skill-progress" data-progress="95"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
            <div class="technologies">
                <h3>Technologies I Work With</h3>
                <div class="tech-tags">
                    @foreach($portfolio->technologies ?? [] as $tech)
                    <span class="tech-tag">{{ is_array($tech) ? $tech['name'] : $tech }}</span>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Projects Section -->
    <section class="projects section" id="projects">
        <div class="container">
            <div class="section-header">
                <h2>Featured Projects</h2>
                <p>A selection of my recent work showcasing different technologies and problem-solving approaches.</p>
            </div>
            
            <div class="project-filters">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="web-app">Web App</button>
                <button class="filter-btn" data-filter="terminal">Terminal</button>
                <button class="filter-btn" data-filter="website">Website</button>
            </div>
            
            <div class="projects-grid" id="projectsGrid">
                @foreach($portfolio->projects ?? [] as $project)
                <div class="project-card" data-category="{{ $project['type'] ?? $project['category'] ?? 'all' }}">
                    <div class="project-image">
                        @php
                            $imagePath = 'https://via.placeholder.com/400x300';
                            if (isset($project['image']) && !empty($project['image'])) {
                                // Check if it's a new uploaded image (in storage/app/public/projects)
                                if (file_exists(public_path('storage/projects/' . $project['image']))) {
                                    $imagePath = asset('storage/projects/' . $project['image']);
                                } 
                                // Check if it's an old image (in resources/image)
                                elseif (file_exists(public_path('images/' . $project['image']))) {
                                    $imagePath = asset('images/' . $project['image']);
                                }
                            }
                        @endphp
                        <img src="{{ $imagePath }}" alt="{{ $project['title'] ?? 'Project' }}">
                        <div class="project-overlay">
                            @if(isset($project['live_url']))
                            <a href="{{ $project['live_url'] }}" class="project-link" target="_blank">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            @endif
                            @if(isset($project['github_url']))
                            <a href="{{ $project['github_url'] }}" class="project-link" target="_blank">
                                <i class="fab fa-github"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="project-content">
                        <div class="project-header">
                            <h3>{{ $project['title'] ?? 'Untitled Project' }}</h3>
                            <span class="project-category">{{ ucfirst($project['type'] ?? $project['category'] ?? 'Project') }}</span>
                        </div>
                        <p>{{ $project['description'] ?? '' }}</p>
                        <div class="project-tech">
                            @foreach($project['technologies'] ?? [] as $tech)
                            <span class="tech-tag">{{ $tech }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact section" id="contact">
        <div class="container">
            <div class="section-header">
                <h2>Get In Touch</h2>
                <p>Ready to start your next project? Let's discuss how we can work together to bring your ideas to life.</p>
            </div>
            
            <div class="contact-content">
                <div class="contact-info">
                    <h3>Let's Connect</h3>
                    <p>I'm always interested in new opportunities and exciting projects. Whether you have a question, want to collaborate, or just want to say hi, I'd love to hear from you!</p>
                    
                    <div class="contact-items">
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Email</h4>
                                <a href="mailto:{{ $portfolio->email }}">{{ $portfolio->email }}</a>
                            </div>
                        </div>
                        @if($portfolio->phone)
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Phone</h4>
                                <span>{{ $portfolio->phone }}</span>
                            </div>
                        </div>
                        @endif
                        @if($portfolio->location)
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-details">
                                <h4>Location</h4>
                                <span>{{ $portfolio->location }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="response-info">
                        <h4>Quick Response</h4>
                        <p>I typically respond to emails within 24 hours. For urgent inquiries, feel free to give me a call directly.</p>
                    </div>
                </div>
                
                <div class="contact-form-container">
                    <h3>Send a Message</h3>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-error">{{ session('error') }}</div>
                    @endif
                    <form class="contact-form" id="contactForm" action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Name *</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject *</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>
                        <!-- Honeypot for spam protection -->
                        <input type="text" name="honeypot" style="display: none;">
                        <button type="submit" class="btn btn-primary btn-full" id="submitBtn">
                            <i class="fas fa-paper-plane"></i>
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <p>&copy; {{ date('Y') }} Kenz. All rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <button class="back-to-top" id="backToTop" aria-label="Back to top">
            <i class="fas fa-chevron-up"></i>
        </button>
    </footer>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>
</body>
</html>
