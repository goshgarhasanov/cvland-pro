<script setup lang="ts">
import { LayoutTemplate, FileText, Download } from 'lucide-vue-next'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const steps = [
  { icon: LayoutTemplate, title: 'home.how.step1_title', desc: 'home.how.step1_desc' },
  { icon: FileText, title: 'home.how.step2_title', desc: 'home.how.step2_desc' },
  { icon: Download, title: 'home.how.step3_title', desc: 'home.how.step3_desc' },
]
</script>

<template>
  <section id="how-it-works" class="scroll-mt-24 py-24">
    <div class="mx-auto max-w-7xl px-6">
      <div class="mx-auto max-w-2xl text-center">
        <span class="kicker">{{ t('home.how.kicker') }}</span>
        <h2 class="mt-4 section-title">{{ t('home.how.title') }}</h2>
        <p class="section-subtitle mx-auto">{{ t('home.how.subtitle') }}</p>
      </div>

      <div class="relative mt-16 grid gap-6 md:grid-cols-3">
        <!-- Connecting line -->
        <div class="absolute left-[16%] right-[16%] top-10 hidden h-px bg-gradient-to-r from-transparent via-brand-500/40 to-transparent md:block"></div>

        <div
          v-for="(step, idx) in steps"
          :key="idx"
          class="card card-hover relative"
        >
          <div class="grid h-12 w-12 place-items-center rounded-2xl bg-gradient-to-br from-brand-500 to-fuchsia-500 text-white shadow-lg shadow-brand-600/30">
            <component :is="step.icon" class="h-6 w-6" />
          </div>
          <h3 class="mt-5 text-lg font-semibold text-white">{{ t(step.title) }}</h3>
          <p class="mt-2 text-sm leading-relaxed text-slate-400">{{ t(step.desc) }}</p>
        </div>
      </div>
    </div>
  </section>
</template>
