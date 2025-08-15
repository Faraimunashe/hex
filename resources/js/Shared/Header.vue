<template>
  <header class="sticky top-0 z-50 bg-white shadow px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-6">
      <button @click="$emit('toggle')" class="text-gray-600 hover:text-blue-600 transition">
        <i class="fas fa-bars text-xl"></i>
      </button>

      <h1 class="text-xl font-semibold text-gray-700">Dashboard</h1>

      <div class="relative hidden md:block">
        <input
          type="text"
          placeholder="Search..."
          class="pl-10 pr-4 py-2 text-sm rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700 w-64"
        />
        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
      </div>
    </div>

    <!-- Right Icons & Dropdown -->
    <div class="flex items-center gap-4 relative">

      <!-- Profile Dropdown Trigger -->
      <div class="relative">
        <img
          src="../../assets/userr.jpg"
          alt="User"
          class="w-8 h-8 rounded-full border shadow cursor-pointer"
          @click="showDropdown = !showDropdown"
        />

        <!-- Dropdown -->
        <transition name="fade">
          <div
            v-if="showDropdown"
            class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-100 z-50 overflow-hidden"
          >
            <div class="p-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white text-center">
              <h2 class="font-semibold text-lg">{{ username }}</h2>
              <p class="text-sm text-blue-100">{{ role }}</p>
            </div>
            <ul class="divide-y divide-gray-100">
              <li>
                <a href="/profile" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-50 text-gray-700">
                  <i class="fas fa-user-circle text-blue-600"></i> Profile
                </a>
              </li>
              <li>
                <a href="#" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-50 text-gray-700">
                  <i class="fas fa-cogs text-blue-600"></i> Settings
                </a>
              </li>
              <li>
                <Link href="/logout" method="POST" class="flex items-center gap-2 px-4 py-3 hover:bg-gray-50 text-red-600 font-semibold">
                  <i class="fas fa-sign-out-alt"></i> Logout
                </Link>
              </li>
            </ul>
          </div>
        </transition>
      </div>
    </div>
  </header>
</template>

<script>
export default {
    props: {
        username: String,
        role: String
    },
  data() {
    return {
      showDropdown: false
    };
  },
  mounted() {
    document.addEventListener("click", this.handleClickOutside);
  },
  beforeUnmount() {
    document.removeEventListener("click", this.handleClickOutside);
  },
  methods: {
    handleClickOutside(e) {
      if (!this.$el.contains(e.target)) {
        this.showDropdown = false;
      }
    }
  }
};
</script>

<style scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity 0.2s ease;
    }
    .fade-enter-from, .fade-leave-to {
        opacity: 0;
    }
</style>
