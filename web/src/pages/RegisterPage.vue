<script setup lang="ts">
import { useForm } from 'vee-validate'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { RouterLink, useRouter } from 'vue-router'
import { toTypedSchema } from '@vee-validate/zod'
import { z } from 'zod'

import { useAuthStore } from '@/stores/auth'

const { t } = useI18n()
const auth = useAuthStore()
const router = useRouter()
const serverError = ref<string | null>(null)

const schema = toTypedSchema(
  z
    .object({
      name: z.string().min(2).max(120),
      email: z.string().email(),
      password: z.string().min(8),
      password_confirmation: z.string().min(8),
    })
    .refine((data) => data.password === data.password_confirmation, {
      message: 'Passwords do not match',
      path: ['password_confirmation'],
    }),
)

const { handleSubmit, defineField, errors } = useForm({ validationSchema: schema })
const [name, nameAttrs] = defineField('name')
const [email, emailAttrs] = defineField('email')
const [password, passwordAttrs] = defineField('password')
const [confirm, confirmAttrs] = defineField('password_confirmation')

const onSubmit = handleSubmit(async (values) => {
  serverError.value = null
  try {
    await auth.register(values)
    await router.push({ name: 'dashboard' })
  } catch (error) {
    serverError.value = (error as Error & { response?: { data?: { message?: string } } })
      .response?.data?.message ?? 'Registration failed'
  }
})
</script>

<template>
  <h1 class="text-center text-2xl font-bold text-slate-900 dark:text-white">{{ t('auth.register_title') }}</h1>
  <p class="mt-2 text-center text-sm text-slate-500">{{ t('auth.register_subtitle') }}</p>

  <form class="mt-8 space-y-4" @submit.prevent="onSubmit">
    <div>
      <label class="block text-sm font-medium">{{ t('auth.name') }}</label>
      <input v-model="name" v-bind="nameAttrs" class="input mt-1" type="text" autocomplete="name" />
      <p v-if="errors.name" class="mt-1 text-xs text-red-600">{{ errors.name }}</p>
    </div>

    <div>
      <label class="block text-sm font-medium">{{ t('auth.email') }}</label>
      <input v-model="email" v-bind="emailAttrs" class="input mt-1" type="email" autocomplete="email" />
      <p v-if="errors.email" class="mt-1 text-xs text-red-600">{{ errors.email }}</p>
    </div>

    <div>
      <label class="block text-sm font-medium">{{ t('auth.password') }}</label>
      <input v-model="password" v-bind="passwordAttrs" class="input mt-1" type="password" autocomplete="new-password" />
      <p v-if="errors.password" class="mt-1 text-xs text-red-600">{{ errors.password }}</p>
    </div>

    <div>
      <label class="block text-sm font-medium">{{ t('auth.password_confirmation') }}</label>
      <input v-model="confirm" v-bind="confirmAttrs" class="input mt-1" type="password" autocomplete="new-password" />
      <p v-if="errors.password_confirmation" class="mt-1 text-xs text-red-600">{{ errors.password_confirmation }}</p>
    </div>

    <p v-if="serverError" class="rounded-lg bg-red-50 p-3 text-sm text-red-700 dark:bg-red-950 dark:text-red-300">
      {{ serverError }}
    </p>

    <button type="submit" class="btn-primary w-full" :disabled="auth.loading">
      {{ auth.loading ? t('common.loading') : t('auth.submit_register') }}
    </button>

    <p class="text-center text-sm text-slate-500">
      {{ t('auth.have_account') }}
      <RouterLink :to="{ name: 'auth.login' }" class="font-medium text-brand-600 hover:underline">
        {{ t('nav.login') }}
      </RouterLink>
    </p>
  </form>
</template>
