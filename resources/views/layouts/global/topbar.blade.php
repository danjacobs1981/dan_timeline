<div id="topbar">
	<div class="nav-menu">
		<input type="checkbox" id="nav_menu" />
		<label for="nav_menu">
			<span class="fa-stack">
				<i class="fa-solid fa-circle fa-stack-2x"></i>
				<i class="fa-regular fa-circle fa-stack-2x"></i>
				<i class="fa-solid fa-bars fa-stack-1x"></i>
			</span>
		</label>
		<div class="nav-menu-section">
			<nav class="reveal__wrapper">
				<div class="reveal__header">
					<strong>Menu</strong>
					<span class="fa-stack reveal__close">
						<i class="fa-solid fa-circle fa-stack-2x"></i>
						<i class="fa-solid fa-xmark fa-stack-1x"></i>
					</span>
				</div>
				<div class="reveal__body">
					<ul>
						<li>
							<a href="#">Link</a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
						<li>
							<a href="#">Link</a>
						</li>
					</ul>
				</div>
			</nav>
		</div>
	</div>
    <a href="{{ route('home.show') }}" title="">
		<img src="{{ Vite::asset('resources/images/timelined-logo.svg') }}" alt="timelined.net"/>
    </a>
	<div class="nav-search">
		<input type="checkbox" id="nav_search" />
		<label for="nav_search">
			<span class="fa-stack">
				<i class="fa-solid fa-circle fa-stack-2x"></i>
				<i class="fa-regular fa-circle fa-stack-2x"></i>
				<i class="fa-solid fa-magnifying-glass fa-stack-1x"></i>
			</span>
		</label>
		<form class="nav-search-section">
			<label for="nav_search">
				<span class="fa-stack">
					<i class="fa-solid fa-circle fa-stack-2x"></i>
					<i class="fa-regular fa-circle fa-stack-2x"></i>
					<i class="fa-solid fa-arrow-left fa-stack-1x"></i>
				</span>
			</label>
			<div>
				<input type="text" name="search" placeholder="Search for timelines..." autocomplete="off" />
				<button>
					<i class="fa-solid fa-magnifying-glass"></i>
				</button>
			</div>
		</form>
	</div>
    <ul>
		<li class="nav-create">
			<a href="{{ route('timelines.create') }}" class="btn" title="">
				<i class="fa-solid fa-circle-plus"></i>Create<em>&nbsp;a timeline</em>
			</a>
		</li>
		@auth
		<li class="nav-notifications">
			<a href="#" title="Notifications">
				<span class="fa-stack">
					<i class="fa-solid fa-circle fa-stack-2x"></i>
					<i class="fa-regular fa-circle fa-stack-2x"></i>
					<i class="fa-solid fa-bell fa-stack-1x"></i>
				</span>
			</a>
		</li>
		<li>
			<a href="{{ route('dashboard.show') }}" title="Dashboard">
				<span class="fa-stack">
					<i class="fa-solid fa-circle fa-stack-2x"></i>
					<i class="fa-regular fa-circle fa-stack-2x"></i>
					<i class="fa-solid fa-user fa-stack-1x"></i>
				</span><em>{{ auth()->user()->username }}</em>
			</a>
		</li>
		@endauth
		@guest
		<li class="nav-register">
			<a href="{{ route('register.show') }}" title="">
				<em>Sign Up</em>
			</a>
		</li>
		<li class="nav-login">
			<a href="{{ route('login.showModal') }}" data-modal data-modal-class="modal-login" data-modal-size="modal-md" data-modal-clickclose="false">
				<span class="fa-stack">
					<i class="fa-solid fa-circle fa-stack-2x"></i>
					<i class="fa-regular fa-circle fa-stack-2x"></i>
					<i class="fa-solid fa-user fa-stack-1x"></i>
				</span><em>Log In</em>
			</a>
		</li>
		@endguest
    </ul>
</div>
