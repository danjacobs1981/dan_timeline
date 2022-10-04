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
			<nav>
				<span class="fa-stack">
					<i class="fa-solid fa-circle fa-stack-2x"></i>
					<i class="fa-solid fa-xmark fa-stack-1x"></i>
				</span>
				<h3>Menu</h3>
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
			</nav>
		</div>
	</div>
    <a href="{{ route('home.index') }}" title="">
		
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
				<input type="text" placeholder="Search for timelines..." />
				<button>
					<i class="fa-solid fa-magnifying-glass"></i>
				</button>
			</div>
		</form>
	</div>
    <ul>
		<li class="nav-create">
			<a href="#" class="btn" title="">
				<i class="fa-solid fa-circle-plus"></i>Create<em>&nbsp;a timeline</em>
			</a>
		</li>
		@auth
		<li class="nav-notifications">
			<a href="#" title="">
				<span class="fa-stack">
					<i class="fa-solid fa-circle fa-stack-2x"></i>
					<i class="fa-regular fa-circle fa-stack-2x"></i>
					<i class="fa-solid fa-bell fa-stack-1x"></i>
				</span>
			</a>
		</li>
		<li>
			<a href="#" title="">
				<span class="fa-stack">
					<i class="fa-solid fa-circle fa-stack-2x"></i>
					<i class="fa-regular fa-circle fa-stack-2x"></i>
					<i class="fa-solid fa-user fa-stack-1x"></i>
				</span><em>{{auth()->user()->username}}</em>
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
			<a href="{{ route('login.showModal') }}" data-modal data-modal_size="modal-lg">
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
