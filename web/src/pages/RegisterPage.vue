<script setup lang="ts">
import { useForm } from 'vee-validate'
import { Eye, EyeOff, Mail, Lock, User, AlertCircle } from 'lucide-vue-next'
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { RouterLink, useRouter } from 'vue-router'
import { toTypedSchema } from '@vee-validate/zod'
import { z } from 'zod'

import { useAuthStore } from '@/stores/auth'

const { t } = useI18n()
const auth = useAuthStore()
const router = useRouter()
const serverError = ref<string | null>(null)
const showPassword = ref(false)

const schema = toTypedSchema(
  z
    .object({
      name: z.string().min(2, t('auth.errors.name_min')).max(120),
      email: z.string().email(t('auth.errors.invalid_email')),
      password: z.string().min(8, t('auth.errors.password_min')),
      password_confirmation: z.string().min(8),
    })
    .refine((data) => data.password === data.password_confirmation, {
      message: t('auth.errors.password_mismatch'),
      path: ['password_confirmation'],
    }),
)

const { handleSubmit, defineField, errors, values } = useForm({ validationSchema: schema })
const [name, nameAttrs] = defineField('name')
const [email, emailAttrs] = defineField('email')
const [password, passwordAttrs] = defineField('password')
const [confirm, confirmAttrs] = defineField('password_confirmation')

// Password strength indicator
const passwordStrength = computed(() => {
  const pw = values.password ?? ''
  if (!pw) return { level: 0, label: '', color: '' }
  let score = 0
  if (pw.length >= 8) score++
  if (pw.length >= 12) score++
  if (/[A-Z]/.test(pw) && /[a-z]/.test(pw)) score++
  if (/\d/.test(pw)) score++
  if (/[^A-Za-z0-9]/.test(pw)) score++
  if (score <= 2) return { level: 1, label: 'Zəif', color: 'bg-rose-500' }
  if (score <= 3) return { level: 2, label: 'Orta', color: 'bg-amber-500' }
  return { level: 3, label: 'Güclü', color: 'bg-emerald-500' }
})

const onSubmit = handleSubmit(async (vals) => {
  serverError.value = null
  try {
    await auth.register(vals)
    await router.push({ name: 'dashboard' })
  } catch (error) {
    const apiMsg = (error as Error & { response?: { data?: { message?: string } } })
      .response?.data?.message
    serverError.value = apiMsg ?? t('auth.errors.register_failed')
  }
})
</script>

<template>
  <div>
    <div class="text-center">
      <h1 class="font-display text-3xl font-bold text-white sm:text-4xl">{{ t('auth.register_title') }}</h1>
      <p class="mt-3 text-sm text-slate-400">{{ t('auth.register_subtitle') }}</p>
    </div>

    <form class="mt-10 space-y-5" @submit.prevent="onSubmit" novalidate>
      <div>
        <label class="block text-sm font-medium text-slate-300" for="name">{{ t('auth.name') }}</label>
        <div class="relative mt-1.5">
          <User class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" />
          <input
            id="name"
            v-model="name"
            v-bind="nameAttrs"
            class="input pl-10"
            type="text"
            autocomplete="name"
            placeholder="Aysu Məmmədova"
          />
        </div>
        <p v-if="errors.name" class="mt-1.5 text-xs text-rose-400">{{ errors.name }}</p>
      </div>

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
        <label class="block text-sm font-medium text-slate-300" for="password">{{ t('auth.password') }}</label>
        <div class="relative mt-1.5">
          <Lock class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" />
          <input
            id="password"
            v-model="password"
            v-bind="passwordAttrs"
            class="input pl-10 pr-10"
            :type="showPassword ? 'text' : 'password'"
            autocomplete="new-password"
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
        <div v-if="passwordStrength.level > 0" class="mt-2 flex items-center gap-2">
          <div class="flex flex-1 gap-1">
            <div
              v-for="i in 3"
              :key="i"
              class="h-1 flex-1 rounded-full transition-colors"
              :class="i <= passwordStrength.level ? passwordStrength.color : 'bg-slate-800'"
            ></div>
          </div>
          <span class="text-xs font-medium text-slate-400">{{ passwordStrength.label }}</span>
        </div>
        <p v-if="errors.password" class="mt-1.5 text-xs text-rose-400">{{ errors.password }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-slate-300" for="password_confirmation">{{ t('auth.password_confirmation') }}</label>
        <div class="relative mt-1.5">
          <Lock class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-500" />
          <input
            id="password_confirmation"
            v-model="confirm"
            v-bind="confirmAttrs"
            class="input pl-10"
            :type="showPassword ? 'text' : 'password'"
            autocomplete="new-password"
            placeholder="••••••••"
          />
        </div>
        <p v-if="errors.password_confirmation" class="mt-1.5 text-xs text-rose-400">{{ errors.password_confirmation }}</p>
      </div>

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
        {{ auth.loading ? t('common.loading') : t('auth.submit_register') }}
      </button>

      <p class="text-center text-sm text-slate-400">
        {{ t('auth.have_account') }}
        <RouterLink :to="{ name: 'auth.login' }" class="font-semibold text-brand-400 hover:text-brand-300">
          {{ t('nav.login') }}
        </RouterLink>
      </p>
    </form>
  </div>
</template>
