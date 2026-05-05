<script setup lang="ts">
import { useQuery } from '@tanstack/vue-query'
import { Plus, FileText, Pencil, Eye, Download } from 'lucide-vue-next'
import { useI18n } from 'vue-i18n'
import { RouterLink } from 'vue-router'

import { api } from '@/lib/api'
import type { ApiCollection, Cv } from '@/types/api'

const { t } = useI18n()

const { data, isLoading } = useQuery({
  queryKey: ['cvs'],
  queryFn: async () => {
    const response = await api.get<ApiCollection<Cv>>('/v1/cvs')
    return response.data
  },
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
        <h1 class="font-display text-3xl font-bold tracking-tight text-white sm:text-4xl">{{ t('cvs.title') }}</h1>
        <p class="mt-2 text-slate-400">{{ t('cvs.subtitle') }}</p>
      </div>
      <RouterLink :to="{ name: 'templates.index' }" class="btn-primary">
        <Plus class="h-4 w-4" />
        {{ t('cvs.create') }}
      </RouterLink>
    </header>

    <div v-if="isLoading" class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <div v-for="i in 6" :key="i" class="card animate-pulse">
        <div class="h-3 w-32 rounded bg-slate-800" />
        <div class="mt-3 h-2 w-24 rounded bg-slate-800" />
      </div>
    </div>

    <div v-else-if="data && data.data.length > 0" class="mt-10 overflow-hidden rounded-2xl border border-slate-800 bg-slate-900/40">
      <ul class="divide-y divide-slate-800">
        <li
          v-for="cv in data.data"
          :key="cv.id"
          class="flex flex-col items-start justify-between gap-4 p-5 transition-colors hover:bg-slate-900/60 sm:flex-row sm:items-center"
        >
          <div class="flex items-center gap-4">
            <div class="grid h-11 w-11 place-items-center rounded-xl border border-slate-800 bg-slate-900 text-brand-300">
              <FileText class="h-5 w-5" />
            </div>
            <div>
              <p class="font-semibold text-white">{{ cv.title }}</p>
              <p class="mt-0.5 flex items-center gap-2 text-xs text-slate-400">
                <span
                  class="rounded-full border px-2 py-0.5 text-[10px] font-medium uppercase tracking-wider"
                  :class="statusStyles[cv.status] ?? statusStyles.draft"
                >
                  {{ statusLabels[cv.status] ?? cv.status }}
                </span>
                <span>·</span>
                <span>{{ t('cvs.updated') }} {{ new Date(cv.updated_at).toLocaleDateString('az-AZ') }}</span>
              </p>
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-2">
            <button class="btn-ghost text-xs">
              <Eye class="h-3.5 w-3.5" />
              {{ t('cvs.preview') }}
            </button>
            <button class="btn-ghost text-xs">
              <Download class="h-3.5 w-3.5" />
              {{ t('cvs.download') }}
            </button>
            <button class="btn-secondary text-xs">
              <Pencil class="h-3.5 w-3.5" />
              {{ t('cvs.edit') }}
            </button>
          </div>
        </li>
      </ul>
    </div>

    <div v-else class="card mt-10 flex flex-col items-center justify-center py-16 text-center">
      <div class="grid h-14 w-14 place-items-center rounded-2xl bg-brand-500/10 text-brand-300">
        <FileText class="h-7 w-7" />
      </div>
      <h3 class="mt-5 text-lg font-semibold text-white">{{ t('dashboard.empty_title') }}</h3>
      <p class="mt-2 max-w-sm text-sm text-slate-400">{{ t('dashboard.empty_subtitle') }}</p>
      <RouterLink :to="{ name: 'templates.index' }" class="btn-primary mt-6">
        {{ t('dashboard.browse_templates') }}
      </RouterLink>
    </div>
  </section>
</template>
