            </main>
        </div>
    </div>
    
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
        
        // Confirm delete actions
        function confirmDelete(message = 'Are you sure you want to delete this item?') {
            return confirm(message);
        }
        
        // Mobile sidebar functionality
        function toggleMobileSidebar() {
            const sidebar = document.querySelector('.mobile-sidebar');
            const overlay = document.getElementById('mobileOverlay');
            
            if (sidebar.classList.contains('open')) {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
            } else {
                sidebar.classList.add('open');
                overlay.classList.add('open');
            }
        }
        
        // Close sidebar when clicking overlay
        document.getElementById('mobileOverlay').addEventListener('click', function() {
            toggleMobileSidebar();
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.mobile-sidebar');
            const menuBtn = document.querySelector('.mobile-menu-btn');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !menuBtn.contains(event.target) && 
                sidebar.classList.contains('open')) {
                toggleMobileSidebar();
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.querySelector('.mobile-sidebar');
            const overlay = document.getElementById('mobileOverlay');
            
            if (window.innerWidth > 768) {
                sidebar.classList.remove('open');
                overlay.classList.remove('open');
            }
        });
        
        // User dropdown functionality for desktop
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }
        
        // User dropdown functionality for mobile
        function toggleMobileUserDropdown() {
            const dropdown = document.getElementById('mobileUserDropdown');
            dropdown.classList.toggle('hidden');
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const desktopContainer = document.getElementById('userDropdownContainer');
            const desktopDropdown = document.getElementById('userDropdown');
            const mobileContainer = document.getElementById('mobileUserDropdownContainer');
            const mobileDropdown = document.getElementById('mobileUserDropdown');
            
            // Close desktop dropdown
            if (desktopContainer && desktopDropdown) {
                if (!desktopContainer.contains(event.target) && !desktopDropdown.classList.contains('hidden')) {
                    desktopDropdown.classList.add('hidden');
                }
            }
            
            // Close mobile dropdown
            if (mobileContainer && mobileDropdown) {
                if (!mobileContainer.contains(event.target) && !mobileDropdown.classList.contains('hidden')) {
                    mobileDropdown.classList.add('hidden');
                }
            }
        });
        
        // Touch-friendly table scrolling
        document.addEventListener('DOMContentLoaded', function() {
            const tables = document.querySelectorAll('.table-responsive');
            tables.forEach(table => {
                let isScrolling = false;
                
                table.addEventListener('touchstart', function() {
                    isScrolling = true;
                });
                
                table.addEventListener('touchend', function() {
                    setTimeout(() => {
                        isScrolling = false;
                    }, 100);
                });
                
                table.addEventListener('touchmove', function(e) {
                    if (isScrolling) {
                        e.preventDefault();
                        this.scrollLeft += e.touches[0].clientX - e.touches[0].clientX;
                    }
                });
            });
        });
    </script>
</body>
</html>

