<template>
  <div
    :class="[ 
      'fixed top-0 left-0 h-full bg-white shadow-lg z-40 transition-transform duration-300',
      isOpen ? 'translate-x-0 w-64' : '-translate-x-full w-64',
      'md:translate-x-0 md:w-64'
    ]"
  >
    <!-- Sidebar header -->

      <div class="flex items-center gap-2 pt-2 pl-2 mb-2">
        <router-link to="/dashboard/admin" class="flex items-center">
          <img src="/assets/img/logo.png" alt="logo" class="w-20 h-20 rounded-4xl object-cover" />
          <span class="font-semibold text-4xl -ml-2">PitStop</span>
        </router-link>
  </div>

    <!-- <div class="flex items-center px-9 py-6">
      <router-link to="/dashboard/admin" class="flex items-center gap-2">
        <img src="/assets/img/logo.jpeg" alt="logo" class="w-8 h-8 rounded-4xl object-cover" />
        <span class="font-semibold text-2xl">PitStop</span>
      </router-link>
    </div> -->

    <!-- Sidebar menu -->
    <div class="pt-0 px-4 pb-2">
      <div v-for="(menu, i) in sidebarData.uiLink" :key="i" class="mb-2">
        
        <!-- Single link (Dashboard, dll.) -->
        <template v-if="menu.type === 'link'">
          <router-link
            :to="menu.link"
            class="flex items-center gap-3 p-2 rounded hover:bg-blue-700"
            exact-active-class="font-semibold text-blue-400" 
          >
            <font-awesome-icon :icon="menu.icon" />
            {{ menu.title }}
          </router-link>
        </template>

        <!-- Sub menu (Inventory, Services, Operasi) -->
        <template v-else-if="menu.type === 'sub'">
          <div>
            <!-- Parent -->
            <div
              class="flex items-center gap-3 p-2 rounded cursor-pointer hover:bg-blue-700"
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

            <!-- Children -->
            <div
              class="submenu ml-6 mt-1"
              :class="{ open: expandedIndex === i }"
            >
              <router-link
                v-for="(child, j) in menu.children"
                :key="j"
                :to="child.link"
                class="block p-2 pl-4 rounded hover:bg-blue-700"
                active-class="text-blue-400 font-semibold"
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
