<template>
  <Head :title="`${exam.module?.code} - ${exam.module?.name}`" />
  <div class="p-6 bg-gray-100 min-h-screen space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
          <i class="fas fa-file-alt text-blue-600"></i>
          {{ exam.module?.code }} - {{ exam.module?.name }}
        </h1>
        <div class="text-sm text-gray-600">
          {{ exam.exam_date }} • {{ exam.start_time }} - {{ exam.end_time }} • {{ exam.venue?.name || 'TBA' }}
        </div>
      </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
      <!-- Invigilators -->
      <div class="bg-white rounded-xl shadow p-4">
        <h2 class="font-semibold text-gray-800 mb-3">Invigilators</h2>
        <div class="flex flex-wrap gap-2">
          <span v-for="inv in exam.invigilations" :key="inv.id" class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">{{ inv.user?.name }}</span>
        </div>
      </div>

      <!-- Exam Rules / Guidelines -->
      <div class="bg-white rounded-xl shadow p-4">
        <h2 class="font-semibold text-gray-800 mb-3">Exam Rules & Guidelines</h2>
        <ul class="list-disc pl-6 space-y-2 text-sm text-gray-700">
          <li v-for="(rule, idx) in rules" :key="idx">{{ rule }}</li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import Layout from '../../Shared/Layout.vue';

export default {
  layout: Layout,
  props: { exam: Object, rules: Array },
};
</script>

<style scoped>
</style>


