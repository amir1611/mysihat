<nav style="display: flex; justify-content: space-between; align-items: center; padding: 10px; background-color: white;">
    <!-- Hamburger Menu for Mobile View -->
    <div class="dropdown d-md-none">
        <button id="sidebarToggleTop" class="btn btn-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-bars"></i>
        </button>
        <div class="dropdown-menu shadow animated--grow-in" aria-labelledby="sidebarToggleTop">
            @foreach (config('patient-navigation.items') as $item)
                <a class="dropdown-item" href="{{ route($item['route']) }}">
                    <i class="{{ $item['icon'] }}"></i> {{ $item['name'] }}
                </a>
            @endforeach
        </div>
    </div>

    <ul style="list-style: none; padding: 0; margin: 0; display: flex; justify-content: flex-end; margin-left: auto;">
        <li style="position: relative; margin-left: 20px;">
            <a href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"
                style="text-decoration: none; color: black; display: flex; align-items: center;">
                <span style="display: inline-block;  margin-right: 10px;">{{ Auth::user()->name }}</span>
                <img src="{{ asset('build/assets/profile-circle.svg') }}" alt="Profile"
                    style="width: 40px; height: 40px; border-radius: 50%;">
            </a>

            <div style="position: absolute; top: 160px; right: 0; transform: translateY(-100%); background: white; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); min-width: 150px; max-width: 200px; z-index: 1000; padding: 10px; display: none;"
                id="dropdownMenu">




                <a href="{{ route('patient.dashboard') }}"
                    style="text-decoration: none; color: black; display: flex; align-items: center; padding: 8px 12px; width: 100%;">
                    <i class="fas fa-user" style="margin-right: 8px;"></i>
                    My Profile
                </a>

                <hr style="margin: 5px 0;">

                <a href="#" data-toggle="modal" data-target="#logoutModal"
                    style="text-decoration: none; color: black; display: flex; align-items: center; padding: 8px 12px; width: 100%;">
                    <i class="fas fa-sign-out-alt" style="margin-right: 8px;"></i>
                    Logout
                </a>
            </div>
        </li>
    </ul>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userDropdown = document.getElementById('userDropdown');
        if (userDropdown) {
            userDropdown.addEventListener('click', function() {
                const dropdownMenu = document.getElementById('dropdownMenu');
                if (dropdownMenu) {
                    dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' :
                        'block';
                }
            });
        }
    });
</script>
