<template>
  <aside class="w-64 bg-gray-800 text-white min-h-screen p-4">
    <nav>
      <ul>
        <!-- Tombol Beranda -->
        <li class="mb-4">
          <router-link
            :to="{ name: 'Home' }"
            class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700"
          >
            <font-awesome-icon icon="home" />
            <span>Beranda</span>
          </router-link>
        </li>

        <li>
          <button
            @click="toggleDropdown('inventory')"
            class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-gray-700 focus:outline-none"
          >
            <div class="flex items-center gap-2">
              <font-awesome-icon icon="tools" />
              <span>Inventory</span>
            </div>
            <font-awesome-icon :icon="dropdownOpen.inventory ? 'chevron-up' : 'chevron-down'" />
          </button>

          <ul
            v-show="dropdownOpen.inventory"
            class="mt-1 pl-8 space-y-1 text-gray-300"
          >
            <li>
              <router-link
                :to="{ name: 'ItemTypes' }"
                class="block py-1 hover:text-white"
              >
                Item Types
              </router-link>
            </li>
            <li>
              <router-link
                :to="{ name: 'ItemCategories' }"
                class="block py-1 hover:text-white"
              >
                Item Categories
              </router-link>
            </li>
            <li>
              <router-link
                to="/inventory/items"
                class="block py-1 hover:text-white"
              >
                Items
              </router-link>
            </li>
          </ul>
        </li>

        <li class="mt-4">
          <button
            @click="toggleDropdown('settings')"
            class="flex items-center justify-between w-full px-3 py-2 rounded hover:bg-gray-700 focus:outline-none"
          >
            <div class="flex items-center gap-2">
              <font-awesome-icon icon="cog" />
              <span>Settings</span>
            </div>
            <font-awesome-icon :icon="dropdownOpen.settings ? 'chevron-up' : 'chevron-down'" />
          </button>

          <ul
            v-show="dropdownOpen.settings"
            class="mt-1 pl-8 space-y-1 text-gray-300"
          >
            <li>
              <router-link
                to="/settings/profile"
                class="block py-1 hover:text-white"
              >
                Profile
              </router-link>
            </li>
            <li>
              <router-link
                to="/settings/account"
                class="block py-1 hover:text-white"
              >
                Account
              </router-link>
            </li>
          </ul>
        </li>
      </ul>
    </nav>
  </aside>
</template>

<script setup>
import { reactive } from 'vue'

const dropdownOpen = reactive({
  inventory: false,
  settings: false,
})

function toggleDropdown(menu) {
  dropdownOpen[menu] = !dropdownOpen[menu]
}
</script>
