<template>
  <Head title="My Exam Timetable" />
  <div class="p-6 bg-gray-100 min-h-screen space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        <i class="fas fa-calendar-alt text-blue-600"></i> My Exam Timetable
      </h1>
      <div class="flex items-center gap-2">
        <input v-model="search" type="text" placeholder="Search module code/name..." class="px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:ring-blue-400 text-sm w-64" />
        <a href="/student/timetables/pdf" class="px-3 py-2 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">
          <i class="fas fa-file-pdf"></i> Download PDF
        </a>
      </div>
    </div>

    <div class="space-y-4">
      <div v-for="(day, date) in groupedEvents" :key="date" class="bg-white rounded-xl shadow">
        <div class="px-4 py-2 bg-blue-50 rounded-t-xl text-blue-800 font-semibold">{{ date }}</div>
        <div class="p-4 space-y-3">
          <div v-for="ev in day" :key="ev.id" class="flex items-center justify-between border rounded-lg p-3 hover:bg-gray-50">
            <div>
              <div class="font-semibold">{{ ev.title }}</div>
              <div class="text-sm text-gray-600">{{ ev.start_time }} - {{ ev.end_time }} • {{ ev.venue || 'TBA' }}</div>
            </div>
            <Link :href="`/student/timetable/${ev.id}`" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">View</Link>
          </div>
        </div>
      </div>
      <div v-if="Object.keys(groupedEvents).length === 0" class="text-center text-gray-500 py-12">No upcoming exams.</div>
    </div>
  </div>
</template>

<script>
import Layout from '../../Shared/Layout.vue';
import { computed, ref, watch } from 'vue';
import { router, Link } from '@inertiajs/vue3';

export default {
  layout: Layout,
  components: { Link },
  props: { events: Array, filters: Object },
  setup(props) {
    const search = ref(props.filters?.search || '');

    watch(search, (value) => {
      router.get('/student/timetable', { search: value }, { preserveState: true, replace: true });
    });

    const groupedEvents = computed(() => {
      const groups = {};
      for (const ev of props.events || []) {
        if (!groups[ev.date]) groups[ev.date] = [];
        groups[ev.date].push(ev);
      }
      return groups;
    });

    return { search, groupedEvents };
  },
};
</script>

<style scoped>
</style>


