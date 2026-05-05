<script setup lang="ts">
import { Mail, Phone, MapPin, Briefcase, GraduationCap, Award } from 'lucide-vue-next'
</script>

<template>
  <div class="relative mx-auto w-full max-w-md">
    <!-- Floating accent cards -->
    <div class="absolute -left-6 top-12 z-10 hidden animate-float rounded-2xl border border-emerald-500/30 bg-emerald-500/10 px-3 py-2 text-xs font-medium text-emerald-300 shadow-xl backdrop-blur sm:block">
      ATS-uyğun
    </div>
    <div class="absolute -right-4 top-40 z-10 hidden animate-float rounded-2xl border border-amber-500/30 bg-amber-500/10 px-3 py-2 text-xs font-medium text-amber-300 shadow-xl backdrop-blur sm:block" style="animation-delay: 1s">
      AI köməkçisi
    </div>
    <div class="absolute -right-2 bottom-20 z-10 hidden animate-float rounded-2xl border border-sky-500/30 bg-sky-500/10 px-3 py-2 text-xs font-medium text-sky-300 shadow-xl backdrop-blur sm:block" style="animation-delay: 2s">
      PDF hazırdır
    </div>

    <!-- CV mock -->
    <div class="relative aspect-[3/4] rounded-3xl border border-slate-800 bg-gradient-to-br from-slate-900 via-slate-900 to-slate-950 p-6 shadow-2xl shadow-brand-900/30">
      <!-- Header -->
      <div class="flex items-center gap-3 border-b border-slate-800 pb-4">
        <div class="grid h-12 w-12 place-items-center rounded-full bg-gradient-to-br from-brand-500 to-fuchsia-500 text-base font-bold text-white">
          AM
        </div>
        <div class="flex-1">
          <div class="text-sm font-bold text-white">Aysu Məmmədova</div>
          <div class="text-[10px] uppercase tracking-wider text-brand-300">Senior Frontend Developer</div>
        </div>
      </div>

      <!-- Contact -->
      <div class="mt-4 grid grid-cols-2 gap-2 text-[10px] text-slate-400">
        <div class="flex items-center gap-1.5"><Mail class="h-3 w-3 text-brand-400" /> aysu@mail.com</div>
        <div class="flex items-center gap-1.5"><Phone class="h-3 w-3 text-brand-400" /> +994 50 555</div>
        <div class="flex items-center gap-1.5"><MapPin class="h-3 w-3 text-brand-400" /> Bakı</div>
        <div class="flex items-center gap-1.5"><Award class="h-3 w-3 text-brand-400" /> 5+ il təcrübə</div>
      </div>

      <!-- Section: Experience -->
      <div class="mt-5">
        <div class="flex items-center gap-2">
          <Briefcase class="h-3.5 w-3.5 text-brand-400" />
          <div class="text-[10px] font-bold uppercase tracking-wider text-slate-300">Təcrübə</div>
        </div>
        <div class="mt-2 space-y-2">
          <div class="space-y-1">
            <div class="h-2 w-3/4 rounded-full bg-slate-700" />
            <div class="h-1.5 w-1/2 rounded-full bg-slate-800" />
            <div class="space-y-1 pt-1">
              <div class="h-1 w-full rounded-full bg-slate-800" />
              <div class="h-1 w-5/6 rounded-full bg-slate-800" />
            </div>
          </div>
          <div class="space-y-1 pt-1">
            <div class="h-2 w-2/3 rounded-full bg-slate-700" />
            <div class="h-1.5 w-2/5 rounded-full bg-slate-800" />
            <div class="space-y-1 pt-1">
              <div class="h-1 w-full rounded-full bg-slate-800" />
              <div class="h-1 w-4/6 rounded-full bg-slate-800" />
            </div>
          </div>
        </div>
      </div>

      <!-- Section: Education -->
      <div class="mt-5">
        <div class="flex items-center gap-2">
          <GraduationCap class="h-3.5 w-3.5 text-brand-400" />
          <div class="text-[10px] font-bold uppercase tracking-wider text-slate-300">Təhsil</div>
        </div>
        <div class="mt-2 space-y-1">
          <div class="h-2 w-3/5 rounded-full bg-slate-700" />
          <div class="h-1.5 w-2/5 rounded-full bg-slate-800" />
        </div>
      </div>

      <!-- Skills -->
      <div class="mt-5 flex flex-wrap gap-1.5">
        <span v-for="skill in ['Vue', 'TypeScript', 'Tailwind', 'Node.js', 'AWS']" :key="skill" class="rounded-full border border-brand-500/30 bg-brand-500/10 px-2 py-0.5 text-[9px] font-medium text-brand-300">
          {{ skill }}
        </span>
      </div>
    </div>
  </div>
</template>
