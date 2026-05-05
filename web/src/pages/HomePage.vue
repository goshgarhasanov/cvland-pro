<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { RouterLink } from 'vue-router'

const { t } = useI18n()
</script>

<template>
  <section class="mx-auto max-w-7xl px-6 py-16 sm:py-24">
    <div class="grid items-center gap-16 lg:grid-cols-2">
      <div>
        <span class="inline-flex items-center rounded-full border border-brand-200 bg-brand-50 px-3 py-1 text-xs font-medium text-brand-700 dark:border-brand-800 dark:bg-brand-950 dark:text-brand-300">
          {{ t('app.tagline') }}
        </span>
        <h1 class="mt-6 font-display text-4xl font-bold tracking-tight text-slate-900 sm:text-6xl dark:text-white">
          {{ t('home.hero_title') }}
        </h1>
        <p class="mt-6 max-w-xl text-lg text-slate-600 dark:text-slate-400">
          {{ t('home.hero_subtitle') }}
        </p>
        <div class="mt-8 flex flex-wrap gap-3">
          <RouterLink :to="{ name: 'auth.register' }" class="btn-primary">
            {{ t('home.cta_primary') }}
          </RouterLink>
          <RouterLink :to="{ name: 'templates.index' }" class="btn-secondary">
            {{ t('home.cta_secondary') }}
          </RouterLink>
        </div>

        <dl class="mt-12 grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div class="card">
            <dt class="text-3xl font-bold text-brand-600">7s</dt>
            <dd class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ t('home.stat_seven_seconds') }}</dd>
          </div>
          <div class="card">
            <dt class="text-3xl font-bold text-brand-600">+40%</dt>
            <dd class="mt-2 text-sm text-slate-600 dark:text-slate-400">{{ t('home.stat_callbacks') }}</dd>
          </div>
        </dl>
      </div>

      <div class="relative">
        <div class="card aspect-[3/4] bg-gradient-to-br from-brand-50 to-white p-8 shadow-2xl dark:from-brand-950 dark:to-slate-900">
          <div class="space-y-4">
            <div class="h-3 w-32 rounded-full bg-brand-200 dark:bg-brand-800"></div>
            <div class="h-2 w-48 rounded-full bg-slate-200 dark:bg-slate-700"></div>
            <div class="h-2 w-40 rounded-full bg-slate-200 dark:bg-slate-700"></div>
            <div class="my-6 h-px bg-slate-200 dark:bg-slate-800"></div>
            <div class="h-3 w-24 rounded-full bg-brand-200 dark:bg-brand-800"></div>
            <div class="space-y-2">
              <div class="h-2 w-full rounded-full bg-slate-200 dark:bg-slate-700"></div>
              <div class="h-2 w-5/6 rounded-full bg-slate-200 dark:bg-slate-700"></div>
              <div class="h-2 w-4/6 rounded-full bg-slate-200 dark:bg-slate-700"></div>
            </div>
            <div class="my-6 h-px bg-slate-200 dark:bg-slate-800"></div>
            <div class="h-3 w-20 rounded-full bg-brand-200 dark:bg-brand-800"></div>
            <div class="space-y-2">
              <div class="h-2 w-full rounded-full bg-slate-200 dark:bg-slate-700"></div>
              <div class="h-2 w-3/4 rounded-full bg-slate-200 dark:bg-slate-700"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
