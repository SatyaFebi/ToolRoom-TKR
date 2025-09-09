<template>
  <div
    :class="[
      'fixed top-0 left-0 h-full bg-white shadow-lg z-40 transition-transform duration-300',
      isOpen ? 'translate-x-0 w-64' : '-translate-x-full w-64',
      'md:translate-x-0 md:w-64'
    ]"
  >
    <!-- Sidebar header -->
    <div class="flex items-center p-4 border-b border-gray-200">
      <router-link to="/dashboard/admin" class="flex items-center gap-5">
        <img src="/assets/img/cat_pfp2.jpeg" alt="logo" class="w-8 h-8 rounded-2xl" />
        <span class="font-semibold text-xl">Autonix</span>
      </router-link>
    </div>

    <!-- Sidebar menu -->
    <div class="p-4">
      <div v-for="(menu, i) in sidebarData.uiLink" :key="i" class="mb-2">
        <!-- Single link -->
        <template v-if="menu.type === 'link'">
          <router-link
            :to="menu.link"
            class="flex items-center gap-3 p-2 rounded hover:bg-gray-100"
            exact-active-class="font-semibold text-blue-700 bg-blue-50 rounded"
          >
            <font-awesome-icon :icon="menu.icon" />
            {{ menu.title }}
          </router-link>
        </template>

        <!-- Sub menu (dorong ke bawah) -->
        <template v-else-if="menu.type === 'sub'">
          <div>
            <div
              class="flex items-center gap-3 p-2 rounded hover:bg-gray-100 cursor-pointer"
              @click="toggleExpand(i)"
            >
              <font-awesome-icon :icon="menu.icon" />
              {{ menu.title }}

              <font-awesome-icon
                :icon="['fas', 'chevron-right']"
                class="ml-auto transition-transform duration-300"
                :class="{ 'rotate-90': expandedIndex === i }"
              />
            </div>

            <div
              class="submenu ml-6 mt-1"
              :class="{ open: expandedIndex === i }"
            >
              <router-link
                v-for="(child, j) in menu.children"
                :key="j"
                :to="child.link"
                class="block p-2 pl-4 rounded hover:bg-gray-100"
                active-class="text-blue-700 font-semibold bg-blue-50 rounded"
              >
                {{ child.title }}
              </router-link>
            </div>
          </div>
        </template>
      </div>
    </div>
  </div>
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

defineProps({
  isOpen: Boolean
})

onMounted(() => {
  sidebarData.uiLink.forEach((menu, i) => {
    if (menu.type === 'sub') {
      const isActiveChild = menu.children.some((child) =>
        route.path.startsWith(child.link)
      )
      if (isActiveChild) {
        expandedIndex.value = i
      }
    }
  })
})
</script>

<style scoped>
.submenu {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease;
}

.submenu.open {
  max-height: 500px; /* harus lebih tinggi dari isi submenu */
}
</style>
