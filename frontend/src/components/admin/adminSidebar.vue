<template>
  <div
    :class="[
      'fixed top-0 left-0 h-full bg-white shadow-lg z-40 transition-transform duration-300',
      isOpen ? 'translate-x-0 w-64' : '-translate-x-full w-64',
      'md:translate-x-0 md:w-64'
    ]"
  >
    <!-- Sidebar content -->
    <div class="flex items-center p-4 border-b border-gray-200">
      <router-link to="/dashboard/admin" class="flex items-center gap-6">
        <img src="#" alt="logo" class="w-8 h-8" />
        <span class="font-bold text-lg">Dickity</span>  
      </router-link>
    </div>

    <div class="p-4 ">
      <div v-for="menu, i in sidebarData.uiLink" :key="i" class="mb-2">
        <template v-if="menu.type === 'link'">
          <router-link 
            :to="menu.link" 
            class="flex items-center font-semibold gap-2 p-2 rounded hover:bg-gray-100"
            exact-active-class="font-semibold text-blue-700 bg-blue-50 rounded"
          >
            <font-awesome-icon :icon="menu.icon" /> 
            {{ menu.title }}
          </router-link>
        </template>

        <!-- Sub-menu -->
        <template v-else-if="menu.type === 'sub'">
          <div>
            <div 
              class="flex items-center font-semibold gap-2 p-2 rounded hover:bg-gray-100 cursor-pointer"
              @click="toggleExpand(i)"
            >
              <font-awesome-icon :icon="menu.icon" />
              {{ menu.title }}

              <!-- Chevron -->
              <font-awesome-icon
                :icon="['fas', 'chevron-right']"
                class="ml-auto transition-transform duration-300 "
                :class="{ 'rotate-90': expandedIndex === i }"
              />
            </div>

            <transition name="slide-fade">
              <div v-if="expandedIndex === i" class="ml-6 mt-1 relative">
              <transition-group>
                <router-link 
                  v-for="(child, j) in menu.children"
                  :key="j"
                  :to="child.link"
                  class="block p-2 pl-4 rounded relative hover:bg-gray-100"
                  active-class="text-blue-700 font-semibold bg-blue-50 rounded"
                >
                  <!-- Garis vertikal -->
                  <span 
                  class="absolute top-0 bottom-0 left-0 w-px bg-gray-300"
                  :class="j === menu.children.length - 1 ? 'h-1/2' : 'h-full'"></span>
                  
                  <!-- Garis horizontal -->
                  <span class="absolute left-0 top-1/2 w-3 h-px bg-gray-300"></span>
                  {{ child.title }}
                </router-link>
              </transition-group>
              </div>
            </transition>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import sidebarData from '@/data/uiList.json'

const expandedIndex = ref(null)

const toggleExpand = (i) => {
  expandedIndex.value = expandedIndex.value === i ? null : i
}

defineProps({
  isOpen: Boolean
})


</script>
<style scoped>

.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.3s ease;
}

.slide-fade-enter-from {
  opacity: 0;
  transform: translateY(-5px);
}

.slide-fade-enter-to {
  opacity: 1;
  transform: translateY(0);
}

.slide-fade-leave-from {
  opacity: 1;
  transform: translateY(0);
}

.slide-fade-leave-to {
  opacity: 0;
  transform: translateY(-5px);
}

.list-enter-active, .list-leave-active {
  transition: all 0.2s ease;
}

.list-enter-from, .list-leave-to {
  opacity: 0;
  transform: translateX(-10px);
}
</style>