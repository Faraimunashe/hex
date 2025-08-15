<template>
  <Head title="Exam Reports" />
  <div class="p-6 bg-gray-100 min-h-screen space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        <i class="fas fa-file-alt text-blue-600"></i> Reports
      </h1>
    </div>

    <!-- Create Report -->
    <div class="bg-white rounded-xl shadow p-4">
      <form @submit.prevent="submit" class="space-y-3">
        <textarea v-model="message" rows="4" class="w-full p-3 border rounded focus:ring-2 focus:ring-blue-500" placeholder="Write your report..."></textarea>
        <div class="flex justify-end">
          <button :disabled="loading" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            <span v-if="!loading">Submit</span>
            <span v-else>Submitting...</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Reports List -->
    <div class="bg-white rounded-xl shadow p-4">
      <div class="grid gap-3">
        <div v-for="report in reports.data" :key="report.id" class="border rounded p-3">
          <div class="text-sm text-gray-500">{{ report.created_at }}</div>
          <div class="font-medium">{{ report.comment }}</div>
        </div>
      </div>
      <div class="mt-3"><Pagination :links="reports.links" /></div>
    </div>
  </div>
</template>

<script>
import Layout from '../../Shared/Layout.vue';
import Pagination from '../../Shared/Pagination.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

export default {
  layout: Layout,
  components: { Pagination },
  props: { exam: Object, reports: Object },
  setup(props) {
    const message = ref('');
    const loading = ref(false);

    const submit = () => {
      loading.value = true;
      router.post(`/lecturer/timetable/${props.exam.id}/reports`, { message: message.value }, {
        onFinish: () => { loading.value = false; message.value = ''; }
      });
    };

    return { message, loading, submit };
  },
};
</script>

<style scoped>
</style>


