<template>
    <div class="flex h-screen bg-gray-100">
        <SidebarAdmin/> 
        
        <div class="flex flex-col flex-1">
            <NavbarAdmin />
            
            <main class="flex-1 p-6 overflow-y-auto">
                <router-view></router-view>
            
            </main>
        </div>
    </div>

</template>

<script>
import { ref } from 'vue'
import router from '@/router'
import NavbarAdmin from '@/components/admin/NavbarAdmin.vue'
import SidebarAdmin from '@/components/admin/SidebarAdmin.vue'
</script>