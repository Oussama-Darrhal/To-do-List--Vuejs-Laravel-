<template>
  <div class="mx-auto max-w-md">
    <Card class="shadow-sm">
      <CardHeader>
        <CardTitle>Create an account</CardTitle>
        <CardDescription>Fill in the details to get started</CardDescription>
      </CardHeader>
      <CardContent>
        <form class="grid gap-4" @submit.prevent="onSubmit" enctype="multipart/form-data">
          <div class="grid gap-2">
            <Label for="full_name">Full name</Label>
            <Input id="full_name" v-model="full_name" placeholder="Jane Doe" required />
          </div>
          <div class="grid gap-2">
            <Label for="email">Email</Label>
            <Input id="email" v-model="email" type="email" placeholder="you@example.com" required />
          </div>
          <div class="grid gap-2">
            <Label for="phone">Phone number</Label>
            <Input id="phone" v-model="phone_number" placeholder="" />
          </div>
          <div class="grid gap-2">
            <Label for="address">Address</Label>
            <Input id="address" v-model="address" placeholder="" />
          </div>
          <div class="grid gap-2">
            <Label for="image">Profile image</Label>
            <Input id="image" type="file" @change="onFile" accept="image/*" />
          </div>
          <div class="grid gap-2">
            <Label for="password">Password</Label>
            <Input id="password" v-model="password" type="password" required />
          </div>
          <Button :disabled="auth.loading" type="submit" class="w-full">Create account</Button>
        </form>
        <Alert v-if="auth.error" variant="destructive" class="mt-4">
          <AlertTitle>Error</AlertTitle>
          <AlertDescription>{{ auth.error.message || auth.error }}</AlertDescription>
        </Alert>
      </CardContent>
      <CardFooter>
        <p class="text-sm text-muted-foreground">Have an account?
          <RouterLink class="underline" to="/login">Login</RouterLink>
        </p>
      </CardFooter>
    </Card>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card'
import Button from '@/components/ui/button/Button.vue'
import Input from '@/components/ui/input/Input.vue'
import Label from '@/components/ui/label/Label.vue'
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert'

const auth = useAuthStore()
const router = useRouter()
const full_name = ref('')
const email = ref('')
const phone_number = ref('')
const address = ref('')
const password = ref('')
const file = ref(null)

const onFile = (e) => {
  file.value = e.target.files?.[0] || null
}

const onSubmit = async () => {
  const fd = new FormData()
  fd.append('full_name', full_name.value)
  fd.append('email', email.value)
  if (phone_number.value) fd.append('phone_number', phone_number.value)
  if (address.value) fd.append('address', address.value)
  fd.append('password', password.value)
  if (file.value) fd.append('image', file.value)
  await auth.register(fd)
  router.push({ name: 'tasks' })
}
</script>

<style scoped>
</style>


