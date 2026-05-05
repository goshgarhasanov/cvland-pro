<script setup lang="ts">
import { useQuery } from '@tanstack/vue-query'
import { FileText, Plus, FileCheck, FilePen, ArrowRight, LayoutTemplate } from 'lucide-vue-next'
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { RouterLink } from 'vue-router'

import { api } from '@/lib/api'
import { useAuthStore } from '@/stores/auth'
import type { ApiCollection, Cv } from '@/types/api'

const { t } = useI18n()
const auth = useAuthStore()

const { data, isLoading } = useQuery({
  queryKey: ['cvs'],
  queryFn: async () => {
    const response = await api.get<ApiCollection<Cv>>('/v1/cvs')
    return response.data
  },
})

const totals = computed(() => {
  const list = data.value?.data ?? []
  return {
    total: list.length,
    published: list.filter((c) => c.status === 'published').length,
    drafts: list.filter((c) => c.status === 'draft').length,
  }
})

const statusStyles: Record<string, string> = {
  published: 'border-emerald-500/30 bg-emerald-500/10 text-emerald-300',
  draft: 'border-amber-500/30 bg-amber-500/10 text-amber-300',
  archived: 'border-slate-500/30 bg-slate-500/10 text-slate-300',
}

const statusLabels: Record<string, string> = {
  published: 'Dərc edilib',
  draft: 'Qaralama',
  archived: 'Arxivləşib',
}
</script>

<template>
  <section class="mx-auto max-w-7xl px-6 py-12">
    <header class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-end">
      <div>
        <h1 class="font-display text-3xl font-bold tracking-tight text-white sm:text-4xl">{{ t('dashboard.title') }}</h1>
        <p v-if="auth.user" class="mt-2 text-slate-400">
          {{ t('dashboard.welcome', { name: auth.user.name }) }} — {{ t('dashboard.subtitle') }}
        </p>
      </div>
      <RouterLink :to="{ name: 'cvs.index' }" class="btn-primary">
        <Plus class="h-4 w-4" />
        {{ t('dashboard.create_new') }}
      </RouterLink>
    </header>

    <!-- Stats -->
    <div class="mt-10 grid gap-4 sm:grid-cols-3">
      <div class="card flex items-center gap-4">
        <div class="grid h-12 w-12 place-items-center rounded-2xl bg-brand-500/10 text-brand-300">
          <FileText class="h-6 w-6" />
        </div>
        <div>
          <p class="text-xs font-medium uppercase tracking-wider text-slate-400">{{ t('dashboard.stats.total') }}</p>
          <p class="font-display text-2xl font-bold text-white">{{ totals.total }}</p>
        </div>
      </div>
      <div class="card flex items-center gap-4">
        <div class="grid h-12 w-12 place-items-center rounded-2xl bg-emerald-500/10 text-emerald-300">
          <FileCheck class="h-6 w-6" />
        </div>
        <div>
          <p class="text-xs font-medium uppercase tracking-wider text-slate-400">{{ t('dashboard.stats.published') }}</p>
          <p class="font-display text-2xl font-bold text-white">{{ totals.published }}</p>
        </div>
      </div>
      <div class="card flex items-center gap-4">
        <div class="grid h-12 w-12 place-items-center rounded-2xl bg-amber-500/10 text-amber-300">
          <FilePen class="h-6 w-6" />
        </div>
        <div>
          <p class="text-xs font-medium uppercase tracking-wider text-slate-400">{{ t('dashboard.stats.drafts') }}</p>
          <p class="font-display text-2xl font-bold text-white">{{ totals.drafts }}</p>
        </div>
      </div>
    </div>

    <!-- CV grid -->
    <div class="mt-12">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold text-white">{{ t('dashboard.your_cvs') }}</h2>
      </div>

      <div v-if="isLoading" class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <div v-for="i in 3" :key="i" class="card animate-pulse">
          <div class="h-3 w-24 rounded bg-slate-800" />
          <div class="mt-4 h-2 w-full rounded bg-slate-800" />
          <div class="mt-2 h-2 w-2/3 rounded bg-slate-800" />
        </div>
      </div>

      <div v-else-if="data && data.data.length > 0" class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <article
          v-for="cv in data.data"
          :key="cv.id"
          class="card card-hover group cursor-pointer"
        >
          <div class="flex items-start justify-between gap-3">
            <h3 class="text-base font-semibold text-white group-hover:text-brand-300">{{ cv.title }}</h3>
            <span
              class="rounded-full border px-2 py-0.5 text-[10px] font-medium uppercase tracking-wider"
              :class="statusStyles[cv.status] ?? statusStyles.draft"
            >
              {{ statusLabels[cv.status] ?? cv.status }}
            </span>
          </div>
          <p class="mt-3 text-xs text-slate-400">{{ t('cvs.updated') }} · {{ new Date(cv.updated_at).toLocaleDateString('az-AZ') }}</p>
          <div class="mt-5 flex items-center justify-between text-sm">
            <span class="text-brand-400 transition-transform group-hover:translate-x-0.5">
              {{ t('cvs.edit') }} →
            </span>
          </div>
        </article>
      </div>

      <div v-else class="card mt-5 flex flex-col items-center justify-center py-16 text-center">
        <div class="grid h-14 w-14 place-items-center rounded-2xl bg-brand-500/10 text-brand-300">
          <LayoutTemplate class="h-7 w-7" />
        </div>
        <h3 class="mt-5 text-lg font-semibold text-white">{{ t('dashboard.empty_title') }}</h3>
        <p class="mt-2 max-w-sm text-sm text-slate-400">{{ t('dashboard.empty_subtitle') }}</p>
        <RouterLink :to="{ name: 'templates.index' }" class="btn-primary mt-6">
          {{ t('dashboard.browse_templates') }}
          <ArrowRight class="h-4 w-4" />
        </RouterLink>
      </div>
    </div>
  </section>
</template>
