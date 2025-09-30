<template>
  <div class="container">
    <h1>Login</h1>
    <form @submit.prevent="onSubmit">
      <input v-model="email" type="email" placeholder="Email" required />
      <input
        v-model="password"
        type="password"
        placeholder="Password"
        required
      />
      <button :disabled="auth.loading" type="submit">Login</button>
      <p v-if="auth.error" class="error">
        {{ auth.error.message || auth.error }}
      </p>
    </form>
    <router-link to="/register">No account? Register</router-link>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const auth = useAuthStore();
const router = useRouter();
const email = ref("");
const password = ref("");

const onSubmit = async () => {
  await auth.login({ email: email.value, password: password.value });
  router.push({ name: "tasks" });
};
</script>

<style scoped>
.container {
  max-width: 420px;
  margin: 2rem auto;
  display: grid;
  gap: 0.75rem;
}
input {
  padding: 0.6rem 0.8rem;
  border: 1px solid #ddd;
  border-radius: 8px;
}
button {
  padding: 0.6rem 0.8rem;
  border: none;
  background: #111827;
  color: #fff;
  border-radius: 8px;
  cursor: pointer;
}
.error {
  color: #b91c1c;
}
</style>
