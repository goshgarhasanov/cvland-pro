<script setup lang="ts">
import { useForm } from 'vee-validate'
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { toTypedSchema } from '@vee-validate/zod'
import { z } from 'zod'

import { useAuthStore } from '@/stores/auth'

const { t } = useI18n()
const auth = useAuthStore()
const router = useRouter()
const route = useRoute()
const serverError = ref<string | null>(null)

const schema = toTypedSchema(
  z.object({
    email: z.string().email(),
    password: z.string().min(1),
    remember: z.boolean().optional(),
  }),
)

const { handleSubmit, defineField, errors } = useForm({ validationSchema: schema })
const [email, emailAttrs] = defineField('email')
const [password, passwordAttrs] = defineField('password')
const [remember, rememberAttrs] = defineField('remember')

const onSubmit = handleSubmit(async (values) => {
  serverError.value = null
  try {
    await auth.login({
      email: values.email,
      password: values.password,
      remember: values.remember,
    })
    const redirect = (route.query.redirect as string | undefined) ?? '/dashboard'
    await router.push(redirect)
  } catch (error) {
    serverError.value = (error as Error & { response?: { data?: { message?: string } } })
      .response?.data?.message ?? 'Login failed'
  }
})
</script>

<template>
  <h1 class="text-center text-2xl font-bold text-slate-900 dark:text-white">{{ t('auth.login_title') }}</h1>
  <p class="mt-2 text-center text-sm text-slate-500">{{ t('auth.login_subtitle') }}</p>

  <form class="mt-8 space-y-4" @submit.prevent="onSubmit">
    <div>
      <label class="block text-sm font-medium">{{ t('auth.email') }}</label>
      <input v-model="email" v-bind="emailAttrs" class="input mt-1" type="email" autocomplete="email" />
      <p v-if="errors.email" class="mt-1 text-xs text-red-600">{{ errors.email }}</p>
    </div>

    <div>
      <label class="block text-sm font-medium">{{ t('auth.password') }}</label>
      <input v-model="password" v-bind="passwordAttrs" class="input mt-1" type="password" autocomplete="current-password" />
      <p v-if="errors.password" class="mt-1 text-xs text-red-600">{{ errors.password }}</p>
    </div>

    <label class="flex items-center gap-2 text-sm">
      <input v-model="remember" v-bind="rememberAttrs" type="checkbox" class="rounded" />
      {{ t('auth.remember') }}
    </label>

    <p v-if="serverError" class="rounded-lg bg-red-50 p-3 text-sm text-red-700 dark:bg-red-950 dark:text-red-300">
      {{ serverError }}
    </p>

    <button type="submit" class="btn-primary w-full" :disabled="auth.loading">
      {{ auth.loading ? t('common.loading') : t('auth.submit_login') }}
    </button>

    <p class="text-center text-sm text-slate-500">
      {{ t('auth.no_account') }}
      <RouterLink :to="{ name: 'auth.register' }" class="font-medium text-brand-600 hover:underline">
        {{ t('nav.register') }}
      </RouterLink>
    </p>
  </form>
</template>
