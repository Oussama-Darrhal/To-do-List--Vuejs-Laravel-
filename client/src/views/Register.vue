<template>
  <div class="container">
    <h1>Register</h1>
    <form @submit.prevent="onSubmit">
      <input v-model="full_name" placeholder="Full name" required />
      <input v-model="email" type="email" placeholder="Email" required />
      <input v-model="phone_number" placeholder="Phone number" />
      <input v-model="address" placeholder="Address" />
      <input v-model="password" type="password" placeholder="Password" required />
      <button :disabled="auth.loading" type="submit">Create account</button>
      <p v-if="auth.error" class="error">{{ auth.error.message || auth.error }}</p>
    </form>
    <router-link to="/login">Have an account? Login</router-link>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const auth = useAuthStore()
const router = useRouter()
const full_name = ref('')
const email = ref('')
const phone_number = ref('')
const address = ref('')
const password = ref('')

const onSubmit = async () => {
  await auth.register({ full_name: full_name.value, email: email.value, phone_number: phone_number.value || undefined, address: address.value || undefined, password: password.value })
  router.push({ name: 'tasks' })
}
</script>

<style scoped>
.container { max-width: 480px; margin: 2rem auto; display: grid; gap: .75rem; }
input { padding: .6rem .8rem; border: 1px solid #ddd; border-radius: 8px; }
button { padding: .6rem .8rem; border: none; background: #111827; color: #fff; border-radius: 8px; cursor: pointer; }
.error { color: #b91c1c; }
</style>


