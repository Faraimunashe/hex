<template>
  <Head title="Edit Examination" />
  <vue3-snackbar top right :duration="4000" />

  <div class="p-6 min-h-screen bg-gray-100">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-600 mb-6 flex items-center gap-2">
      <i class="fa fa-home text-gray-400"></i>
      <span>/</span>
      <a href="/admin/examinations" class="hover:text-blue-600">Examinations</a>
      <span>/</span>
      <span class="font-semibold text-blue-700">Edit</span>
    </nav>

    <!-- Card -->
    <div class="bg-white rounded-xl shadow-lg p-8 max-w-3xl mx-auto border border-blue-100">
      <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i class="fa fa-clipboard-list text-blue-600"></i> Edit Examination
      </h2>

      <form @submit.prevent="submit" class="space-y-5">
        <!-- Module -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">Module</label>
          <select
            v-model="form.module_id"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.module_id }"
            required
          >
            <option value="" disabled>Select a module</option>
            <option v-for="module in modules" :key="module.id" :value="module.id">
              {{ module.name }}
            </option>
          </select>
          <div v-if="form.errors.module_id" class="text-red-500 text-sm mt-1">{{ form.errors.module_id }}</div>
        </div>

        <!-- Venue -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">Venue</label>
          <select
            v-model="form.venue_id"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.venue_id }"
            required
          >
            <option value="" disabled>Select a venue</option>
            <option v-for="venue in venues" :key="venue.id" :value="venue.id">
              {{ venue.name }}
            </option>
          </select>
          <div v-if="form.errors.venue_id" class="text-red-500 text-sm mt-1">{{ form.errors.venue_id }}</div>
        </div>

        <!-- Exam Date -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">Exam Date</label>
          <input
            v-model="form.exam_date"
            type="date"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.exam_date }"
            required
          />
          <div v-if="form.errors.exam_date" class="text-red-500 text-sm mt-1">{{ form.errors.exam_date }}</div>
        </div>

        <!-- Start Time -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">Start Time</label>
          <input
            v-model="form.start_time"
            type="time"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.start_time }"
            required
          />
          <div v-if="form.errors.start_time" class="text-red-500 text-sm mt-1">{{ form.errors.start_time }}</div>
        </div>

        <!-- End Time -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">End Time</label>
          <input
            v-model="form.end_time"
            type="time"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.end_time }"
            required
          />
          <div v-if="form.errors.end_time" class="text-red-500 text-sm mt-1">{{ form.errors.end_time }}</div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-2 pt-4">
          <a href="/admin/examinations" class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 text-gray-700">Cancel</a>
          <button
            type="submit"
            :disabled="loading"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2"
          >
            <i class="fa fa-save"></i>
            <span v-if="!loading">Update Examination</span>
            <span v-else>Updating...</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from '../../../Shared/Layout.vue';
import { useForm } from '@inertiajs/vue3';
import { useSnackbar } from 'vue3-snackbar';

export default {
  layout: Layout,
  props: {
    examination: Object,
    modules: Array,
    venues: Array,
  },
  data() {
    return {
      loading: false,
      snackbar: useSnackbar(),
      form: useForm({
        module_id: this.examination.module_id,
        venue_id: this.examination.venue_id,
        exam_date: this.examination.exam_date,
        start_time: this.examination.start_time,
        end_time: this.examination.end_time,
      }),
    };
  },
  methods: {
    submit() {
      this.loading = true;
      this.form.put(`/admin/examinations/${this.examination.id}`, {
        onSuccess: () => {
          this.snackbar.add({
            type: 'success',
            text: 'Examination updated successfully!',
          });
        },
        onError: (errors) => {
          const message =
            errors?.error ||
            this.form.errors?.[Object.keys(this.form.errors)[0]] ||
            'Something went wrong.';
          this.snackbar.add({
            type: 'error',
            text: message,
          });
        },
        onFinish: () => {
          this.loading = false;
        },
      });
    },
  },
};
</script>


