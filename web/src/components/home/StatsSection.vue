<script setup lang="ts">
import { Clock, TrendingUp, LayoutTemplate, Globe2 } from 'lucide-vue-next'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const stats = [
  { icon: Clock, value: 'home.stats.seconds_value', label: 'home.stats.seconds_label', desc: 'home.stats.seconds_desc' },
  { icon: TrendingUp, value: 'home.stats.callbacks_value', label: 'home.stats.callbacks_label', desc: 'home.stats.callbacks_desc' },
  { icon: LayoutTemplate, value: 'home.stats.templates_value', label: 'home.stats.templates_label', desc: 'home.stats.templates_desc' },
  { icon: Globe2, value: 'home.stats.languages_value', label: 'home.stats.languages_label', desc: 'home.stats.languages_desc' },
]
</script>

<template>
  <section class="relative border-y border-slate-800/50 bg-slate-900/30 py-16">
    <div class="mx-auto max-w-7xl px-6">
      <dl class="grid grid-cols-2 gap-x-6 gap-y-10 lg:grid-cols-4">
        <div v-for="(stat, idx) in stats" :key="idx" class="relative">
          <div class="flex items-center gap-3">
            <div class="grid h-10 w-10 place-items-center rounded-xl border border-brand-500/30 bg-brand-500/10 text-brand-300">
              <component :is="stat.icon" class="h-5 w-5" />
            </div>
            <div>
              <dt class="text-xs font-medium uppercase tracking-wider text-slate-400">{{ t(stat.label) }}</dt>
              <dd class="mt-0.5 font-display text-2xl font-bold text-white sm:text-3xl">{{ t(stat.value) }}</dd>
            </div>
          </div>
          <p class="mt-3 text-sm text-slate-400">{{ t(stat.desc) }}</p>
        </div>
      </dl>
    </div>
  </section>
</template>
