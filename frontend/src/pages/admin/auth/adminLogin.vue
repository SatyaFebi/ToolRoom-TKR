<template>
    <div>
        Admin Login
    <div>
    <form @submit.prevent="login" class="flex flex-col">
      <input type="email" placeholder="Email" v-model="email" class="border rounded-lg py-1 px-3 w-70">
      <input type="password" placeholder="Password" v-model="password" class="border rounded-lg py-1 px-3 w-70 mt-1">
      <button type="submit" class="border rounded-lg py-1 px-3 w-70 mt-2 bg-green-600 text-white hover:bg-green-800 duration-300 hover:text-slate-300">Submit</button>
    
    </form>
    </div>
  </div>
</template>

<script setup>
  import { apiRequest } from '@/api/apiRequest'
  import { ref } from 'vue'
  import router from '@/router'

  const email = ref('')
  const password = ref('')
  const errorMessage = ref('')
  const successMessage = ref('')
  const isLoading = ref(false)

  const login = async () => {
    errorMessage.value = ''
    successMessage.value = ''
    isLoading.value = true

    try {
      const response = await apiRequest(isLoading, 'post', 'admin/login', {
        email: email.value,
        password: password.value
      })
  
      if (response.token) {
        localStorage.setItem('authToken', response.token)
        router.push('/admin/dashboard')
      }
      console.log(response)
      successMessage.value = 'Login berhasil'

    } catch (error) {
      errorMessage.value = 'Error: ', error
      console.log(error)
    } finally {
        isLoading.value = false
    }
  }

  
</script>