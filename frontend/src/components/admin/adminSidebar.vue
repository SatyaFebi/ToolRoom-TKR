<template>
  <aside
    :class="[ 
      'fixed top-0 left-0 h-full shadow-lg z-40 transition-transform duration-300 ease-in-out backdrop-blur-sm',
      isOpen ? 'translate-x-0 w-64' : '-translate-x-full w-64',
      'md:translate-x-0 md:w-64',
      'bg-gradient-to-b from-blue-200 to-white border-r border-blue-100'
    ]"
  >
    <!-- Header -->
    <div class="flex items-center gap-2 pt-4 pl-4 mb-6">
      <router-link to="/dashboard/admin" class="flex items-center">
        <img src="/assets/img/logo.png" alt="logo" class="w-14 h-14 rounded-2xl object-cover shadow-md" />
        <span class="ml-3 font-bold text-2xl text-gray-800 tracking-tight">Toolroom TKR</span>
      </router-link>
    </div>

    <!-- Menu -->
    <nav class="px-4 space-y-2">
      <div v-for="(menu, i) in sidebarData.uiLink" :key="i">
        <!-- Single Link -->
        <template v-if="menu.type === 'link'">
          <router-link
            :to="menu.link"
            class="group flex items-center gap-3 p-3 rounded-xl text-gray-700 transition-all duration-300 hover:translate-x-2 hover:bg-blue-100 hover:text-blue-700"
            exact-active-class="bg-blue-100 text-blue-700 font-semibold shadow-inner"
          >
            <font-awesome-icon :icon="menu.icon" class="text-gray-500 group-hover:text-blue-600 transition-colors duration-300" />
            <span class="font-medium">{{ menu.title }}</span>
          </router-link>
        </template>

        <!-- Submenu -->
        <template v-else-if="menu.type === 'sub'">
          <div>
            <!-- Parent -->
            <div
              class="flex items-center gap-3 p-3 rounded-xl text-gray-700 cursor-pointer hover:bg-blue-100 hover:text-blue-700 transition-all duration-300"
              @click="toggleExpand(i)"
            >
              <font-awesome-icon :icon="menu.icon" class="text-gray-500 group-hover:text-blue-600" />
              <span class="font-medium">{{ menu.title }}</span>
              <font-awesome-icon
                :icon="['fas', 'chevron-right']"
                class="ml-auto text-gray-400 transition-transform duration-300"
                :class="{ 'rotate-90 text-blue-600': expandedIndex === i }"
              />
            </div>

            <!-- Children -->
            <transition name="slide-fade">
              <div v-if="expandedIndex === i" class="ml-6 mt-1 space-y-1">
                <router-link
                  v-for="(child, j) in menu.children"
                  :key="j"
                  :to="child.link"
                  class="block p-2 pl-4 rounded-lg hover:bg-blue-50 hover:text-blue-700 text-gray-600 transition-all duration-300"
                  active-class="bg-blue-100 text-blue-700 font-semibold shadow-inner"
                >
                  {{ child.title }}
                </router-link>
              </div>
            </transition>
          </div>
        </template>
      </div>
    </nav>
  </aside>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import sidebarData from '@/data/uiList.json'

const expandedIndex = ref(null)
const route = useRoute()

const toggleExpand = (i) => {
  expandedIndex.value = expandedIndex.value === i ? null : i
}

defineProps({ isOpen: Boolean })

onMounted(() => {
  sidebarData.uiLink.forEach((menu, i) => {
    if (menu.type === 'sub') {
      const isActiveChild = menu.children.some((child) => route.path.startsWith(child.link))
      if (isActiveChild) expandedIndex.value = i
    }
  })
})
</script>

<style scoped>
.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.3s ease;
}
.slide-fade-enter-from,
.slide-fade-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}
</style>
