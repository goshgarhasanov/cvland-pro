<script setup lang="ts">
import { ShieldCheck, Sparkles, Languages, Eye, FileDown, Gift } from 'lucide-vue-next'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const features = [
  { icon: ShieldCheck, title: 'home.features.ats_title', desc: 'home.features.ats_desc', gradient: 'from-emerald-500 to-teal-500' },
  { icon: Sparkles, title: 'home.features.ai_title', desc: 'home.features.ai_desc', gradient: 'from-fuchsia-500 to-purple-500' },
  { icon: Languages, title: 'home.features.languages_title', desc: 'home.features.languages_desc', gradient: 'from-sky-500 to-blue-500' },
  { icon: Eye, title: 'home.features.preview_title', desc: 'home.features.preview_desc', gradient: 'from-amber-500 to-orange-500' },
  { icon: FileDown, title: 'home.features.pdf_title', desc: 'home.features.pdf_desc', gradient: 'from-rose-500 to-pink-500' },
  { icon: Gift, title: 'home.features.free_title', desc: 'home.features.free_desc', gradient: 'from-brand-500 to-indigo-500' },
]
</script>

<template>
  <section id="features" class="scroll-mt-24 bg-slate-900/30 py-24">
    <div class="mx-auto max-w-7xl px-6">
      <div class="mx-auto max-w-2xl text-center">
        <span class="kicker">{{ t('home.features.kicker') }}</span>
        <h2 class="mt-4 section-title">{{ t('home.features.title') }}</h2>
        <p class="section-subtitle mx-auto">{{ t('home.features.subtitle') }}</p>
      </div>

      <div class="mt-16 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div
          v-for="(feature, idx) in features"
          :key="idx"
          class="card card-hover group"
        >
          <div
            class="grid h-12 w-12 place-items-center rounded-2xl bg-gradient-to-br text-white shadow-lg transition-transform group-hover:scale-110"
            :class="feature.gradient"
          >
            <component :is="feature.icon" class="h-6 w-6" />
          </div>
          <h3 class="mt-5 text-lg font-semibold text-white">{{ t(feature.title) }}</h3>
          <p class="mt-2 text-sm leading-relaxed text-slate-400">{{ t(feature.desc) }}</p>
        </div>
      </div>
    </div>
  </section>
</template>
