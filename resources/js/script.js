// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

// Initialize Application
function initializeApp() {
    // Remove loading overlay
    setTimeout(() => {
        const loadingOverlay = document.getElementById('loadingOverlay');
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
        }
    }, 1500);

    // Initialize components
    initializeTheme();
    initializeNavigation();
    initializeAuth();
    initializeAnimations();
    initializeTypewriter();
    initializeSkillBars();
    initializeEducationTimeline();
    initializeProjectFilters();
    initializeContactForm();
    initializeScrollEffects();
    initializeCounterAnimation();
}

// Authentication Management
function initializeAuth() {
    const logoutBtn = document.getElementById('logoutBtn');
    
    // Check if user is logged in (you can modify this logic based on your auth system)
    const isLoggedIn = checkAuthStatus();
    
    if (isLoggedIn) {
        showLogoutButton();
    }
    
    // Logout button event
    if (logoutBtn) {
        logoutBtn.addEventListener('click', handleLogout);
    }
    
    // Check for admin access (example: check URL parameters or session)
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('admin') === 'true' || sessionStorage.getItem('adminAccess') === 'true') {
        showLogoutButton();
        sessionStorage.setItem('adminAccess', 'true');
    }
}

function checkAuthStatus() {
    // Check various auth indicators
    return (
        sessionStorage.getItem('adminAccess') === 'true' ||
        localStorage.getItem('userToken') !== null ||
        document.cookie.includes('auth_token') ||
        new URLSearchParams(window.location.search).get('admin') === 'true'
    );
}

function showLogoutButton() {
    // Desktop logout button
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.style.display = 'flex';
        logoutBtn.classList.add('show');
        
        // Add subtle entrance animation
        setTimeout(() => {
            logoutBtn.style.opacity = '1';
        }, 100);
    }
    
    
}

function hideLogoutButton() {
    // Desktop logout button
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.classList.add('hide');
        setTimeout(() => {
            logoutBtn.style.display = 'none';
            logoutBtn.classList.remove('hide', 'show');
        }, 300);
    }
    
    // Mobile logout button
    const mobileLogoutContainer = document.getElementById('mobileLogoutContainer');
    if (mobileLogoutContainer) {
        mobileLogoutContainer.classList.add('hide');
        setTimeout(() => {
            mobileLogoutContainer.style.display = 'none';
            mobileLogoutContainer.classList.remove('hide', 'show');
        }, 300);
    }
}

function handleLogout() {
    // Show confirmation dialog
    const confirmed = confirm('Are you sure you want to logout?');
    
    if (confirmed) {
        // Close mobile menu if open
        const mobileNav = document.getElementById('mobileNav');
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        if (mobileNav && mobileNav.classList.contains('active')) {
            mobileNav.classList.remove('active');
            mobileMenuToggle.innerHTML = '<i class="fas fa-bars"></i>';
        }
        
        // Clear all auth data
        sessionStorage.removeItem('adminAccess');
        localStorage.removeItem('userToken');
        
        // Clear auth cookies
        document.cookie = 'auth_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        
        // Hide logout button
        hideLogoutButton();
        
        // Show success notification
        showNotification('Successfully logged out!', 'success');
        
        // Optional: Redirect to login page or refresh
        // window.location.href = '/login.php';
        
        // Or refresh current page after a delay
        setTimeout(() => {
            const url = new URL(window.location);
            url.searchParams.delete('admin');
            window.history.replaceState({}, document.title, url.pathname);
        }, 2000);
    }
}

// Function to manually trigger login (for testing purposes)
function simulateLogin() {
    sessionStorage.setItem('adminAccess', 'true');
    showLogoutButton();
    showNotification('Admin access granted!', 'success');
}

// Add keyboard shortcut for admin access (Ctrl+Shift+A)
document.addEventListener('keydown', (e) => {
    if (e.ctrlKey && e.shiftKey && e.key === 'A') {
        e.preventDefault();
        const password = prompt('Enter admin password:');
        if (password === 'admin123') { // Change this to a secure password
            simulateLogin();
        } else if (password !== null) {
            showNotification('Invalid password!', 'error');
        }
    }
});

// Theme Management
function initializeTheme() {
    const themeToggle = document.getElementById('themeToggle');
    const savedTheme = localStorage.getItem('theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    // Set initial theme
    if (savedTheme) {
        setTheme(savedTheme);
    } else if (systemPrefersDark) {
        setTheme('dark');
    } else {
        setTheme('light');
    }
    
    // Theme toggle event
    themeToggle.addEventListener('click', toggleTheme);
}

function setTheme(theme) {
    const body = document.body;
    const themeIcon = document.querySelector('#themeToggle i');
    
    if (theme === 'dark') {
        body.classList.add('dark');
        themeIcon.className = 'fas fa-sun';
        localStorage.setItem('theme', 'dark');
    } else {
        body.classList.remove('dark');
        themeIcon.className = 'fas fa-moon';
        localStorage.setItem('theme', 'light');
    }
}

function toggleTheme() {
    const isDark = document.body.classList.contains('dark');
    setTheme(isDark ? 'light' : 'dark');
}

// Navigation
function initializeNavigation() {
    const header = document.getElementById('header');
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileNav = document.getElementById('mobileNav');
    const backToTop = document.getElementById('backToTop');
    
    // Scroll effects
    window.addEventListener('scroll', () => {
        const scrolled = window.scrollY > 50;
        header.classList.toggle('scrolled', scrolled);
        
        // Back to top button
        if (backToTop) {
            backToTop.classList.toggle('visible', window.scrollY > 300);
        }
    });
     
    // Back to top functionality
    if (backToTop) {
        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
}

// Smooth scrolling
function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
        const headerHeight = document.getElementById('header').offsetHeight;
        const elementPosition = element.offsetTop - headerHeight - 20;
        
        window.scrollTo({
            top: elementPosition,
            behavior: 'smooth'
        });
    }
}

// Typewriter Effect
function initializeTypewriter() {
    const typewriterElement = document.getElementById('typewriter');
    const cursor = document.getElementById('cursor');
    
    if (!typewriterElement) return;
    
    const texts = [
        'Full Stack Developer',
        'UI/UX Designer',
        'Problem Solver',
        'Code Enthusiast'
    ];
    
    let textIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    let isWaiting = false;
    
    function typeWriter() {
        const currentText = texts[textIndex];
        
        if (isWaiting) {
            setTimeout(() => {
                isWaiting = false;
                isDeleting = true;
                typeWriter();
            }, 2000);
            return;
        }
        
        if (isDeleting) {
            typewriterElement.textContent = currentText.substring(0, charIndex - 1);
            charIndex--;
            
            if (charIndex === 0) {
                isDeleting = false;
                textIndex = (textIndex + 1) % texts.length;
            }
        } else {
            typewriterElement.textContent = currentText.substring(0, charIndex + 1);
            charIndex++;
            
            if (charIndex === currentText.length) {
                isWaiting = true;
            }
        }
        
        const speed = isDeleting ? 50 : 100;
        setTimeout(typeWriter, speed);
    }
    
    // Start typewriter effect after initial delay
    setTimeout(typeWriter, 1000);
}

// Intersection Observer for animations
function initializeAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                
                // Trigger counter animation for stat cards
                if (entry.target.classList.contains('stat-card')) {
                    animateCounter(entry.target.querySelector('.stat-number'));
                }
                
                // Trigger skill bar animation
                if (entry.target.classList.contains('skill-item')) {
                    animateSkillBar(entry.target);
                }
                
                // Trigger tech tag animation
                if (entry.target.classList.contains('tech-tag')) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, Math.random() * 500);
                }
            }
        });
    }, observerOptions);
    
    // Observe elements
    const elementsToObserve = [
        '.section-header',
        '.feature-card',
        '.stat-card',
        '.skill-category',
        '.skill-item',
        '.tech-tag',
        '.education-item',
        '.learning-card',
        '.cert-tag',
        '.project-card',
        '.contact-item'
    ];
    
    elementsToObserve.forEach(selector => {
        document.querySelectorAll(selector).forEach(element => {
            observer.observe(element);
        });
    });
}

// Counter Animation
function initializeCounterAnimation() {
    // This will be triggered by intersection observer
}

function animateCounter(element) {
    if (!element || element.classList.contains('animated')) return;
    
    const target = parseInt(element.getAttribute('data-target'));
    let current = 0;
    const increment = target / 50;
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        element.textContent = Math.floor(current) + (target === 100 ? '%' : '+');
    }, 30);
    
    element.classList.add('animated');
}

// Education Timeline Animation
function initializeEducationTimeline() {
    const timelineLine = document.getElementById('timelineLine');
    const educationItems = document.querySelectorAll('.education-item');
    const certTags = document.querySelectorAll('.cert-tag');
    
    // Animate timeline line when education section comes into view
    const educationSection = document.getElementById('education');
    if (educationSection) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    timelineLine.classList.add('animate');
                    
                    // Animate education items with stagger
                    educationItems.forEach((item, index) => {
                        setTimeout(() => {
                            item.classList.add('visible');
                        }, index * 300 + 500);
                    });
                    
                    // Animate certification tags
                    setTimeout(() => {
                        certTags.forEach((tag, index) => {
                            setTimeout(() => {
                                tag.classList.add('visible');
                            }, index * 100);
                        });
                    }, educationItems.length * 300 + 1000);
                }
            });
        }, { threshold: 0.3 });
        
        observer.observe(educationSection);
    }
    
    // Add click handlers for timeline dots
    const timelineDots = document.querySelectorAll('.timeline-dot');
    timelineDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            // Scroll to the corresponding education item
            const educationItem = dot.closest('.education-item');
            if (educationItem) {
                educationItem.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
            }
            
            // Add pulse animation
            dot.style.animation = 'none';
            setTimeout(() => {
                dot.style.animation = 'pulse 0.6s ease';
            }, 10);
        });
        
        // Hover effect for timeline dots
        dot.addEventListener('mouseenter', () => {
            const educationCard = dot.nextElementSibling;
            if (educationCard) {
                educationCard.style.transform = 'translateY(-3px)';
                educationCard.style.boxShadow = '0 20px 25px -5px rgba(0, 0, 0, 0.1)';
            }
        });
        
        dot.addEventListener('mouseleave', () => {
            const educationCard = dot.nextElementSibling;
            if (educationCard) {
                educationCard.style.transform = '';
                educationCard.style.boxShadow = '';
            }
        });
    });
}

// Skill Bars Animation
function initializeSkillBars() {
    // This will be triggered by intersection observer
}

function animateSkillBar(skillItem) {
    if (skillItem.classList.contains('animated')) return;
    
    const progressBar = skillItem.querySelector('.skill-progress');
    if (!progressBar) return;
    
    const progress = progressBar.getAttribute('data-progress');
    
    setTimeout(() => {
        progressBar.style.width = progress + '%';
    }, 300);
    
    skillItem.classList.add('animated');
}

// Project Filters
function initializeProjectFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const projectCards = document.querySelectorAll('.project-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
            // Filter projects
            projectCards.forEach(card => {
                const category = card.getAttribute('data-category');
                
                if (filter === 'all' || category === filter) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 100);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(30px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
}

// Contact Form
function initializeContactForm() {
    const contactForm = document.getElementById('contactForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (!contactForm) return;
    
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (!validateForm(contactForm)) {
            return;
        }
        
        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        submitBtn.disabled = true;
        
        // Simulate form submission (replace with actual submission)
        setTimeout(() => {
            // Reset form and button
            contactForm.reset();
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Message';
            submitBtn.disabled = false;
            
            // Show success message
            showNotification('Message sent successfully! I\'ll get back to you soon.', 'success');
        }, 2000);
    });
}

function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            showFieldError(input, 'This field is required');
            isValid = false;
        } else if (input.type === 'email' && !isValidEmail(input.value)) {
            showFieldError(input, 'Please enter a valid email address');
            isValid = false;
        } else {
            clearFieldError(input);
        }
    });
    
    return isValid;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showFieldError(input, message) {
    clearFieldError(input);
    
    const errorElement = document.createElement('div');
    errorElement.className = 'field-error';
    errorElement.textContent = message;
    errorElement.style.color = '#dc2626';
    errorElement.style.fontSize = '0.875rem';
    errorElement.style.marginTop = '0.25rem';
    
    input.parentNode.appendChild(errorElement);
    input.style.borderColor = '#dc2626';
}

function clearFieldError(input) {
    const errorElement = input.parentNode.querySelector('.field-error');
    if (errorElement) {
        errorElement.remove();
    }
    input.style.borderColor = '';
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div style="
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : '#3b82f6'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 10000;
            animation: slideInRight 0.3s ease;
        ">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
                ${message}
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Remove after 5 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 5000);
}

// Scroll Effects
function initializeScrollEffects() {
    // Parallax effect for hero background
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const heroBackground = document.querySelector('.hero-bg');
        
        if (heroBackground) {
            heroBackground.style.transform = `translateY(${scrolled * 0.5}px)`;
        }
    });
    
    // Update active nav link based on scroll position
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav a[href^="#"]');
    
    window.addEventListener('scroll', () => {
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop - 100;
            if (pageYOffset >= sectionTop) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    });
}

// Utility Functions
function throttle(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Performance optimizations
window.addEventListener('scroll', throttle(() => {
    // Throttled scroll events
}, 16));

window.addEventListener('resize', debounce(() => {
    // Debounced resize events
}, 250));

// Keyboard navigation
document.addEventListener('keydown', (e) => {
    // ESC key closes mobile menu
    if (e.key === 'Escape') {
        const mobileNav = document.getElementById('mobileNav');
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        
        if (mobileNav && mobileNav.classList.contains('active')) {
            mobileNav.classList.remove('active');
            const icon = mobileMenuToggle.querySelector('i');
            icon.className = 'fas fa-bars';
        }
    }
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 0 0 10px rgba(79, 70, 229, 0);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(79, 70, 229, 0);
        }
    }
`;
document.head.appendChild(style);

// Console easter egg
console.log(`
%c
╔══════════════════════════════════════╗
║                                      ║
║        Welcome to my Portfolio!      ║
║                                      ║
║     Built with HTML, CSS, JS & PHP   ║
║                                      ║
║    Interested in working together?    ║
║       neth.zedlav@gmail.com          ║
║                                      ║
╚══════════════════════════════════════╝
`, 'color: #4f46e5; font-family: monospace;');

// Service Worker for PWA (optional)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('SW registered: ', registration);
            })
            .catch(registrationError => {
                console.log('SW registration failed: ', registrationError);
            });
    });
}