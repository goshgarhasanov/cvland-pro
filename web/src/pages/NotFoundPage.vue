<script setup lang="ts">
import { ArrowLeft } from 'lucide-vue-next'
import { useI18n } from 'vue-i18n'
import { RouterLink } from 'vue-router'

const { t } = useI18n()
</script>

<template>
  <section class="relative mx-auto flex min-h-[70vh] max-w-2xl flex-col items-center justify-center px-6 py-24 text-center">
    <div class="pointer-events-none absolute inset-0 -z-10">
      <div class="absolute left-1/2 top-1/2 h-96 w-96 -translate-x-1/2 -translate-y-1/2 rounded-full bg-brand-600/10 blur-3xl"></div>
    </div>
    <span class="font-display text-8xl font-extrabold gradient-text sm:text-9xl">404</span>
    <h1 class="mt-6 font-display text-3xl font-bold text-white">{{ t('notfound.title') }}</h1>
    <p class="mt-3 max-w-md text-slate-400">{{ t('notfound.subtitle') }}</p>
    <RouterLink :to="{ name: 'home' }" class="btn-primary mt-8">
      <ArrowLeft class="h-4 w-4" />
      {{ t('common.back_home') }}
    </RouterLink>
  </section>
</template>
