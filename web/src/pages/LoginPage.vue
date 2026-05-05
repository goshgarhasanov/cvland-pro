<script setup lang="ts">
import { useForm } from 'vee-validate'
import { Eye, EyeOff, Mail, Lock, AlertCircle } from 'lucide-vue-next'
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
const showPassword = ref(false)

const schema = toTypedSchema(
  z.object({
    email: z.string().email(t('auth.errors.invalid_email')),
    password: z.string().min(1),
    remember: z.boolean().optional(),
  }),
)

const { handleSubmit, defineField, errors } = useForm({ validationSchema: schema })
const [email, emailAttrs] = defineField('email')
const [password, passwordAttrs] = defineField('password')
const [remember, rememberAttrs] = defineField('remember')

/**
 * Only allow same-origin relative paths as the post-login redirect target.
 * Anything else (absolute URL, protocol-relative URL, javascript: URL, etc.)
 * is silently dropped to prevent an open redirect.
 */
function safeRedirect(target: unknown): string {
  if (typeof target !== 'string' || target.length === 0) return '/dashboard'
  // Reject anything that is not a single-leading-slash relative path.
  // This blocks "//evil.com", "https://...", "javascript:..." and "\\evil".
  if (!/^\/[^/\\]/.test(target)) return '/dashboard'
  return target
}

const onSubmit = handleSubmit(async (values) => {
  serverError.value = null
  try {
    await auth.login({
      email: values.email,
      password: values.password,
      remember: values.remember,
    })
    const redirect = safeRedirect(route.query.redirect)
    await router.push(redirect)
  } catch (error) {
    const apiMsg = (error as Error & { response?: { data?: { message?: string } } })
      .response?.data?.message
    serverError.value = apiMsg ?? t('auth.errors.login_failed')
  }
})
</script>

<template>
  <div>
    <div class="text-center">
      <h1 class="font-display text-3xl font-bold text-white sm:text-4xl">{{ t('auth.login_title') }}</h1>
      <p class="mt-3 text-sm text-slate-400">{{ t('auth.login_subtitle') }}</p>
    </div>

    <form class="mt-10 space-y-5" @submit.prevent="onSubmit" novalidate>
      <div>
        <label class="block text-sm font-medium text-slate-300" for="email">{{ t('auth.email') }}</label>
        <div class="relative mt-1.5">
          <Mail class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" />
          <input
            id="email"
            v-model="email"
            v-bind="emailAttrs"
            class="input pl-10"
            type="email"
            autocomplete="email"
            placeholder="ad@nümunə.az"
          />
        </div>
        <p v-if="errors.email" class="mt-1.5 text-xs text-rose-400">{{ errors.email }}</p>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label class="block text-sm font-medium text-slate-300" for="password">{{ t('auth.password') }}</label>
          <a href="#" class="text-xs font-medium text-brand-400 hover:text-brand-300">Şifrəni unutdun?</a>
        </div>
        <div class="relative mt-1.5">
          <Lock class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" />
          <input
            id="password"
            v-model="password"
            v-bind="passwordAttrs"
            class="input pl-10 pr-10"
            :type="showPassword ? 'text' : 'password'"
            autocomplete="current-password"
            placeholder="••••••••"
          />
          <button
            type="button"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 transition-colors hover:text-slate-300"
            @click="showPassword = !showPassword"
            :aria-label="showPassword ? 'Hide password' : 'Show password'"
          >
            <EyeOff v-if="showPassword" class="h-4 w-4" />
            <Eye v-else class="h-4 w-4" />
          </button>
        </div>
        <p v-if="errors.password" class="mt-1.5 text-xs text-rose-400">{{ errors.password }}</p>
      </div>

      <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-300">
        <input
          v-model="remember"
          v-bind="rememberAttrs"
          type="checkbox"
          class="h-4 w-4 rounded border-slate-700 bg-slate-900 text-brand-500 focus:ring-brand-500/30"
        />
        {{ t('auth.remember') }}
      </label>

      <Transition
        enter-active-class="transition duration-200"
        enter-from-class="opacity-0 -translate-y-1"
        enter-to-class="opacity-100"
      >
        <div
          v-if="serverError"
          class="flex items-start gap-2 rounded-xl border border-rose-500/30 bg-rose-500/10 p-3.5 text-sm text-rose-300"
        >
          <AlertCircle class="h-4 w-4 flex-shrink-0 mt-0.5" />
          <span>{{ serverError }}</span>
        </div>
      </Transition>

      <button type="submit" class="btn-primary w-full py-3 text-base" :disabled="auth.loading">
        {{ auth.loading ? t('common.loading') : t('auth.submit_login') }}
      </button>

      <p class="text-center text-sm text-slate-400">
        {{ t('auth.no_account') }}
        <RouterLink :to="{ name: 'auth.register' }" class="font-semibold text-brand-400 hover:text-brand-300">
          {{ t('nav.register') }}
        </RouterLink>
      </p>
    </form>
  </div>
</template>
