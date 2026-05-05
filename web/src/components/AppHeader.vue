<script setup lang="ts">
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { RouterLink, useRouter } from 'vue-router'

import { useAuthStore } from '@/stores/auth'

const { t, locale } = useI18n()
const auth = useAuthStore()
const router = useRouter()

const initials = computed(() =>
  auth.user
    ? auth.user.name
        .split(' ')
        .map((part) => part[0])
        .join('')
        .slice(0, 2)
        .toUpperCase()
    : '',
)

async function handleLogout() {
  await auth.logout()
  await router.push({ name: 'home' })
}

function setLocale(value: 'en' | 'az' | 'ru') {
  locale.value = value
  localStorage.setItem('locale', value)
}
</script>

<template>
  <header class="border-b border-slate-200 bg-white/80 backdrop-blur dark:border-slate-800 dark:bg-slate-950/80">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
      <RouterLink :to="{ name: 'home' }" class="flex items-center gap-2 text-xl font-display font-bold text-brand-700 dark:text-brand-300">
        <span class="grid h-8 w-8 place-items-center rounded-lg bg-brand-600 text-sm text-white">CV</span>
        CVLAND <span class="font-light text-brand-400">PRO</span>
      </RouterLink>

      <nav class="flex items-center gap-1">
        <template v-if="auth.isAuthenticated">
          <RouterLink :to="{ name: 'dashboard' }" class="btn-ghost">{{ t('nav.dashboard') }}</RouterLink>
          <RouterLink :to="{ name: 'cvs.index' }" class="btn-ghost">{{ t('nav.cvs') }}</RouterLink>
          <RouterLink :to="{ name: 'templates.index' }" class="btn-ghost">{{ t('nav.templates') }}</RouterLink>
        </template>
      </nav>

      <div class="flex items-center gap-2">
        <select
          :value="locale"
          @change="setLocale(($event.target as HTMLSelectElement).value as 'en' | 'az' | 'ru')"
          class="input w-auto py-1 text-sm"
        >
          <option value="en">EN</option>
          <option value="az">AZ</option>
          <option value="ru">RU</option>
        </select>

        <template v-if="auth.isAuthenticated">
          <div class="grid h-9 w-9 place-items-center rounded-full bg-brand-100 text-sm font-semibold text-brand-700 dark:bg-brand-900 dark:text-brand-200">
            {{ initials }}
          </div>
          <button class="btn-ghost" @click="handleLogout">{{ t('nav.logout') }}</button>
        </template>
        <template v-else>
          <RouterLink :to="{ name: 'auth.login' }" class="btn-ghost">{{ t('nav.login') }}</RouterLink>
          <RouterLink :to="{ name: 'auth.register' }" class="btn-primary">{{ t('nav.register') }}</RouterLink>
        </template>
      </div>
    </div>
  </header>
</template>
