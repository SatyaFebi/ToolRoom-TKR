<template>
  <div class="frame">
    <img
      class="max-verstatppen"
      alt="Max verstatppen"
      src="/assets/img/bgsamping.jpeg"
    />
    <div class="loginbox">
    <div class="rectangle" />
    <div class="usernametags">
      <img src="/assets/img/user.png" alt="user" class="user">
      <span class="text-wrapper">Email</span>
    </div> 
    <div class="passwd">
      <img src="/assets/img/password.png" alt="user" class="userpasswd">
      <span>Password</span>
    </div>
    <div class="text-wrapper-2">Login</div>
    <img
      class="background-removebg"
      alt="Background removebg"
      src="/assets/img/logo.png"
    />
    <div class="rectangle-email">
      <input type="email" v-model="email" class="input-email" placeholder="Masukan email">
    </div>
    <div class="rectangle-password">
      <input type="password" id="passwordInput" v-model="password" class="input-email" placeholder="Masukan password" />
      <img src="/assets/img/mata.png" alt="show password" class="toggle-eye" @click="togglePassword" />
    </div>
    <div class="rectangle-login" @click="handleLogin">
      <div class="text-wrapper-3" >Login</div>
    </div>
  </div>
    </div>  
</template>

<script setup>

import useAuth from '@/composables/useAuth'
import { ref } from 'vue'
import router from '@/router'

const email = ref('')
const password = ref('')
const rememberMe = ref(false) // Tambahan untuk remember me
const errorMessage = ref('')
const successMessage = ref('')
const isLoading = ref(false)

const { login } = useAuth()

// Cek kalau sebelumnya user pernah centang Remember Me
if (localStorage.getItem('rememberEmail')) {
  email.value = localStorage.getItem('rememberEmail')
}

const handleLogin = async () => {
  isLoading.value = true
  errorMessage.value = ''
  successMessage.value = ''
  try {
    await login(email.value, password.value, rememberMe.value)
    if (rememberMe.value) {
      localStorage.setItem('rememberEmail', email.value)
    } else {
      localStorage.removeItem('rememberEmail')
    }

    successMessage.value = 'Login sukses! Mengalihkan...'
    router.push('/dashboard/admin')
  } catch (err) {
    errorMessage.value = err.response?.data?.message || 'Login gagal. Silakan coba lagi.'
  } finally {
    isLoading.value = false
  }
}

</script>

<style scoped>
.loginbox {
  position: relative;
  top: 120px;   /* geser ke bawah */
  left: 230px;  /* geser ke kanan */
  width: 600px;
  height: 600px;
}

.loginbox .rectangle-email {
  position: absolute;
  top: 219px;
  left: 0px; /* relatif terhadap loginbox */
}

.input-email {
  width: 100%;
  height: 100%;
  border: none;
  padding: 5px 10px;
  font-size: 12px;
  background: transparent;
  box-sizing: border-box;
}

.toggle-eye {
  position: absolute;
  top: 300px; /* sesuaikan dengan posisi input */
  left: 910px; /* sesuaikan agar sejajar dengan input */
  width: 20px;
  height: 20px;
  cursor: pointer;
}

.frame {
  background-color: #133e87;
  width: 100vw;
  height: 100vh;
  position: relative;
  overflow: hidden;

}

.frame .max-verstatppen {
  position: absolute;
  top: 0;
  left: 0;
  width: 40vw;
  height: 100vh;
  object-fit: cover;

}

.frame .passwd {
   position: absolute;
  top: 270px;
  left: 635px; 
  display: flex;              /* aktifkan flexbox */
  align-items: center;        /* sejajarkan vertikal */
  gap: 7px;                   /* jarak antara ikon dan teks */
  font-weight: 560;
  font-size: 13px;
  color: #000000;
  font-family: "Ramabhadra-Regular", Helvetica;

}


.frame .rectangle {
  background: linear-gradient(
    180deg,
    rgba(255, 255, 255, 1) 0%,
    rgba(17, 66, 149, 1) 100%
  );
  border-radius: 6px;
  height: 433px;
  left: 578px;
  position: absolute;
  top: 55px;
  width: 412px;
}

.frame .usernametags {
  color: #000000;
  font-family: "Ramabhadra-Regular", Helvetica;
  font-size: 10px;
  font-weight: 400;
  display: flex;
  align-items: center;
  gap: 8px; /* jarak antara ikon dan teks */
  position: absolute;
  top: 195px;
  left: 630px;

}

.user {
  width: 20px;
  height: 20px;
  object-fit: contain;

}
.userpasswd {
  width: 20px;
  height: 20px;
  object-fit: contain;

}


.frame .div {
  color: #000000;
  font-family: "Ramabhadra-Regular", Helvetica;
  font-size: 10px;
  font-weight: 400;
  left: 650px;
  letter-spacing: 0.50px;
  line-height: normal;
  position: absolute;
  top: 280px;
}

.text-wrapper {
  font-weight: 560;
  font-size: 13px;       /* bikin lebih tebal */
  margin-top: 3px;        /* geser sedikit ke bawah */
  display: inline-block;
}


.passwd {
  font-weight: 560;
  font-size: 13px;       /* bikin lebih tebal */
  margin-top: 3px;        /* geser sedikit ke bawah */
  display: inline-block;
}

.frame .text-wrapper-2 {
  color: #000000;
  font-family: "Ramabhadra-Regular", Helvetica;
  font-size: 32px;
  font-weight: 700;
  left: 735px;
  letter-spacing: 1.60px;
  line-height: normal;
  position: absolute;
  top: 94px;
  width: 155px;
}

.frame .background-removebg {
  aspect-ratio: 0.94;
  height: 53px;
  left: 684px;
  object-fit: cover;
  position: absolute;
  top: 89px;
  width: 50px;
}

.frame .rectangle-email {
  background-color: #d2d3d5;
  border-radius: 6px;
  height: 27px;
  left: 629px;
  position: absolute;
  top: 219px;
  width: 304px;
}

.frame .vector {
  height: 2.39%;
  left: 53.66%;
  position: absolute;
  top: 37.13%;
    width: 20px;
  height: 20px;
  position: absolute;
  object-fit: contain;

}

.frame .img {
  height: 2.57%;
  left: 53.49%;
  position: absolute;
  top: 51.65%;
  width: 20px;
  height: 20px;
  position: absolute;
  object-fit: contain;

}

.frame .vector-2 {
  height: 0;
  left: 54.25%;
  position: absolute;
  top: 52.21%;
  width: 20px;
  height: 20px;
  position: absolute;
  object-fit: contain;

}

.frame .rectangle-password {
  background-color: #d2d3d5;
  border-radius: 6px;
  height: 27px;
  left: 629px;
  position: absolute;
  top: 298px;
  width: 304px;
}

.frame .vector-3 {
  height: 2.94%;
  left: 76.96%;
  position: absolute;
  top: 56.07%;
    width: 20px;
  height: 20px;
  position: absolute;
  object-fit: contain;

}

.frame .rectangle-login {
  background: linear-gradient(
    90deg,
    rgba(17, 66, 149, 1) 34%,
    rgba(5, 21, 47, 1) 100%
  );
  border-radius: 9px;
  box-shadow: 0px 4px 4px #00000040;
  height: 22px;
  left: 841px;
  position: absolute;
  top: 357px;
  width: 78px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;

}

.frame .text-wrapper-3 {
    position: relative;
  text-align: center;
  font-size: 12px;
  font-weight: 600;
  color: #ffffff;

}
</style>
