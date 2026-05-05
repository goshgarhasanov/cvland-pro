<script setup lang="ts">
import { Menu, X, ChevronDown, FileText, LogOut, LayoutDashboard } from 'lucide-vue-next'
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import { RouterLink, useRoute, useRouter } from 'vue-router'

import { useAuthStore } from '@/stores/auth'

const { t, locale } = useI18n()
const auth = useAuthStore()
const router = useRouter()
const route = useRoute()

const scrolled = ref(false)
const mobileOpen = ref(false)
const userOpen = ref(false)
const langOpen = ref(false)

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

const isHome = computed(() => route.name === 'home')

const marketingLinks = [
  { href: '#how-it-works', key: 'nav.how' },
  { href: '#features', key: 'nav.features' },
  { href: '#templates', key: 'nav.templates' },
  { href: '#faq', key: 'nav.faq' },
]

const languages = [
  { code: 'az' as const, label: 'Azərbaycan', flag: 'AZ' },
  { code: 'en' as const, label: 'English', flag: 'EN' },
  { code: 'ru' as const, label: 'Русский', flag: 'RU' },
]

const currentLang = computed(() => languages.find((l) => l.code === locale.value) ?? languages[0])

function setLocale(value: 'en' | 'az' | 'ru') {
  locale.value = value
  localStorage.setItem('locale', value)
  document.documentElement.lang = value
  langOpen.value = false
}

async function handleLogout() {
  userOpen.value = false
  await auth.logout()
  await router.push({ name: 'home' })
}

function onScroll() {
  scrolled.value = window.scrollY > 12
}

function closeMenus() {
  userOpen.value = false
  langOpen.value = false
}

onMounted(() => {
  window.addEventListener('scroll', onScroll, { passive: true })
  onScroll()
  document.documentElement.lang = locale.value
})

onBeforeUnmount(() => {
  window.removeEventListener('scroll', onScroll)
})

watch(() => route.fullPath, () => {
  mobileOpen.value = false
  closeMenus()
})
</script>

<template>
  <header
    class="sticky top-0 z-50 transition-all duration-300"
    :class="scrolled
      ? 'border-b border-slate-800/80 bg-slate-950/80 backdrop-blur-xl'
      : 'border-b border-transparent bg-transparent'"
  >
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-6 py-3.5">
      <!-- Logo -->
      <RouterLink :to="{ name: 'home' }" class="flex items-center gap-2 text-xl font-display font-bold text-white">
        <span class="grid h-9 w-9 place-items-center rounded-xl bg-gradient-to-br from-brand-500 to-fuchsia-500 text-sm font-bold text-white shadow-lg shadow-brand-600/30">
          CV
        </span>
        <span>CVLAND <span class="font-light text-brand-300">PRO</span></span>
      </RouterLink>

      <!-- Desktop nav -->
      <nav v-if="isHome" class="hidden items-center gap-1 lg:flex">
        <a
          v-for="link in marketingLinks"
          :key="link.href"
          :href="link.href"
          class="btn-ghost"
        >
          {{ t(link.key) }}
        </a>
      </nav>

      <nav v-else-if="auth.isAuthenticated" class="hidden items-center gap-1 lg:flex">
        <RouterLink :to="{ name: 'dashboard' }" class="btn-ghost">{{ t('nav.dashboard') }}</RouterLink>
        <RouterLink :to="{ name: 'cvs.index' }" class="btn-ghost">{{ t('nav.cvs') }}</RouterLink>
        <RouterLink :to="{ name: 'templates.index' }" class="btn-ghost">{{ t('nav.templates') }}</RouterLink>
      </nav>

      <!-- Right cluster -->
      <div class="flex items-center gap-2">
        <!-- Language switcher (custom dropdown) -->
        <div class="relative hidden sm:block">
          <button
            type="button"
            class="btn-ghost px-3"
            @click="langOpen = !langOpen; userOpen = false"
          >
            <span class="text-xs font-bold tracking-wider">{{ currentLang.flag }}</span>
            <ChevronDown class="h-3.5 w-3.5" :class="langOpen ? 'rotate-180' : ''" />
          </button>
          <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
          >
            <div
              v-if="langOpen"
              class="absolute right-0 top-full mt-2 w-44 overflow-hidden rounded-xl border border-slate-800 bg-slate-900 py-1 shadow-2xl shadow-slate-950/60"
            >
              <button
                v-for="lang in languages"
                :key="lang.code"
                type="button"
                class="flex w-full items-center justify-between px-4 py-2 text-sm transition-colors hover:bg-slate-800"
                :class="lang.code === locale ? 'text-brand-300' : 'text-slate-300'"
                @click="setLocale(lang.code)"
              >
                <span>{{ lang.label }}</span>
                <span class="text-xs font-bold tracking-wider opacity-60">{{ lang.flag }}</span>
              </button>
            </div>
          </Transition>
        </div>

        <!-- Authenticated user menu -->
        <template v-if="auth.isAuthenticated">
          <div class="relative hidden sm:block">
            <button
              type="button"
              class="flex items-center gap-2 rounded-xl border border-slate-800 bg-slate-900/60 px-2 py-1.5 transition hover:border-slate-700 hover:bg-slate-800"
              @click="userOpen = !userOpen; langOpen = false"
            >
              <span class="grid h-7 w-7 place-items-center rounded-lg bg-gradient-to-br from-brand-500 to-fuchsia-500 text-xs font-bold text-white">
                {{ initials }}
              </span>
              <ChevronDown class="h-3.5 w-3.5 text-slate-400" :class="userOpen ? 'rotate-180' : ''" />
            </button>
            <Transition
              enter-active-class="transition duration-150 ease-out"
              enter-from-class="opacity-0 -translate-y-2"
              enter-to-class="opacity-100 translate-y-0"
              leave-active-class="transition duration-100 ease-in"
              leave-from-class="opacity-100"
              leave-to-class="opacity-0"
            >
              <div
                v-if="userOpen"
                class="absolute right-0 top-full mt-2 w-56 overflow-hidden rounded-xl border border-slate-800 bg-slate-900 shadow-2xl shadow-slate-950/60"
              >
                <div class="border-b border-slate-800 px-4 py-3">
                  <p class="truncate text-sm font-semibold text-white">{{ auth.user?.name }}</p>
                  <p class="truncate text-xs text-slate-400">{{ auth.user?.email }}</p>
                </div>
                <div class="py-1">
                  <RouterLink :to="{ name: 'dashboard' }" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-300 transition-colors hover:bg-slate-800 hover:text-white">
                    <LayoutDashboard class="h-4 w-4" />
                    {{ t('nav.dashboard') }}
                  </RouterLink>
                  <RouterLink :to="{ name: 'cvs.index' }" class="flex items-center gap-3 px-4 py-2 text-sm text-slate-300 transition-colors hover:bg-slate-800 hover:text-white">
                    <FileText class="h-4 w-4" />
                    {{ t('nav.cvs') }}
                  </RouterLink>
                </div>
                <div class="border-t border-slate-800 py-1">
                  <button
                    type="button"
                    class="flex w-full items-center gap-3 px-4 py-2 text-sm text-rose-400 transition-colors hover:bg-rose-500/10"
                    @click="handleLogout"
                  >
                    <LogOut class="h-4 w-4" />
                    {{ t('nav.logout') }}
                  </button>
                </div>
              </div>
            </Transition>
          </div>
        </template>

        <!-- Guest CTAs -->
        <template v-else>
          <RouterLink :to="{ name: 'auth.login' }" class="hidden btn-ghost sm:inline-flex">
            {{ t('nav.login') }}
          </RouterLink>
          <RouterLink :to="{ name: 'auth.register' }" class="hidden btn-primary sm:inline-flex">
            {{ t('nav.register') }}
          </RouterLink>
        </template>

        <!-- Mobile menu button -->
        <button
          type="button"
          class="btn-ghost p-2 lg:hidden"
          @click="mobileOpen = !mobileOpen"
          aria-label="Menu"
        >
          <Menu v-if="!mobileOpen" class="h-5 w-5" />
          <X v-else class="h-5 w-5" />
        </button>
      </div>
    </div>

    <!-- Mobile menu -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0 -translate-y-4"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="mobileOpen"
        class="border-t border-slate-800 bg-slate-950/95 px-6 py-6 backdrop-blur-xl lg:hidden"
      >
        <nav class="flex flex-col gap-1">
          <template v-if="isHome">
            <a
              v-for="link in marketingLinks"
              :key="link.href"
              :href="link.href"
              class="rounded-lg px-4 py-3 text-base font-medium text-slate-300 hover:bg-slate-800 hover:text-white"
              @click="mobileOpen = false"
            >
              {{ t(link.key) }}
            </a>
          </template>
          <template v-else-if="auth.isAuthenticated">
            <RouterLink :to="{ name: 'dashboard' }" class="rounded-lg px-4 py-3 text-base font-medium text-slate-300 hover:bg-slate-800 hover:text-white">{{ t('nav.dashboard') }}</RouterLink>
            <RouterLink :to="{ name: 'cvs.index' }" class="rounded-lg px-4 py-3 text-base font-medium text-slate-300 hover:bg-slate-800 hover:text-white">{{ t('nav.cvs') }}</RouterLink>
            <RouterLink :to="{ name: 'templates.index' }" class="rounded-lg px-4 py-3 text-base font-medium text-slate-300 hover:bg-slate-800 hover:text-white">{{ t('nav.templates') }}</RouterLink>
          </template>
        </nav>

        <div class="mt-6 flex flex-wrap gap-2 border-t border-slate-800 pt-6">
          <button
            v-for="lang in languages"
            :key="lang.code"
            type="button"
            class="rounded-lg border px-3 py-1.5 text-xs font-bold tracking-wider transition"
            :class="lang.code === locale
              ? 'border-brand-500 bg-brand-500/10 text-brand-300'
              : 'border-slate-700 text-slate-400 hover:border-slate-600 hover:text-white'"
            @click="setLocale(lang.code)"
          >
            {{ lang.flag }}
          </button>
        </div>

        <div class="mt-6 grid gap-2">
          <template v-if="auth.isAuthenticated">
            <button class="btn-secondary w-full" @click="handleLogout">{{ t('nav.logout') }}</button>
          </template>
          <template v-else>
            <RouterLink :to="{ name: 'auth.login' }" class="btn-secondary w-full">{{ t('nav.login') }}</RouterLink>
            <RouterLink :to="{ name: 'auth.register' }" class="btn-primary w-full">{{ t('nav.register') }}</RouterLink>
          </template>
        </div>
      </div>
    </Transition>
  </header>
</template>
