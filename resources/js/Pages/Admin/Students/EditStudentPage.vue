<template>
  <Head title="Edit Student" />
  <vue3-snackbar top right :duration="4000" />

  <div class="p-6 min-h-screen bg-gray-100">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-600 mb-6 flex items-center gap-2">
      <i class="fa fa-home text-gray-400"></i>
      <span>/</span>
      <a href="/admin/students" class="hover:text-blue-600">Students</a>
      <span>/</span>
      <span class="font-semibold text-blue-700">Edit</span>
    </nav>

    <!-- Card -->
    <div class="bg-white rounded-xl shadow-lg p-8 max-w-3xl mx-auto border border-blue-100">
      <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i class="fa fa-user-graduate text-blue-600"></i> Edit Student
      </h2>

      <form @submit.prevent="submit" class="space-y-5">
        <!-- Department -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">Department</label>
          <select
            v-model="form.department_id"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.department_id }"
            required
          >
            <option value="" disabled>Select a department</option>
            <option v-for="department in departments" :key="department.id" :value="department.id">
              {{ department.name }}
            </option>
          </select>
          <div v-if="form.errors.department_id" class="text-red-500 text-sm mt-1">{{ form.errors.department_id }}</div>
        </div>

        <!-- Reg Number -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">Registration Number</label>
          <input
            v-model="form.regnum"
            type="text"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.regnum }"
            required
          />
          <div v-if="form.errors.regnum" class="text-red-500 text-sm mt-1">{{ form.errors.regnum }}</div>
        </div>

        <!-- Firstnames -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">Firstnames</label>
          <input
            v-model="form.firstnames"
            type="text"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.firstnames }"
            required
          />
          <div v-if="form.errors.firstnames" class="text-red-500 text-sm mt-1">{{ form.errors.firstnames }}</div>
        </div>

        <!-- Surname -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">Surname</label>
          <input
            v-model="form.surname"
            type="text"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.surname }"
            required
          />
          <div v-if="form.errors.surname" class="text-red-500 text-sm mt-1">{{ form.errors.surname }}</div>
        </div>

        <!-- Gender -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">Gender</label>
          <select
            v-model="form.gender"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.gender }"
            required
          >
            <option value="" disabled>Select gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
          <div v-if="form.errors.gender" class="text-red-500 text-sm mt-1">{{ form.errors.gender }}</div>
        </div>

        <!-- Email -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">Email</label>
          <input
            v-model="form.email"
            type="email"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.email }"
            required
          />
          <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
        </div>

        <!-- Password (optional) -->
        <div>
          <label class="block text-gray-700 font-medium mb-1">Password (optional)</label>
          <input
            v-model="form.password"
            type="password"
            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            :class="{ 'border-red-500': form.errors.password }"
          />
          <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</div>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-2 pt-4">
          <a href="/admin/students" class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-100 text-gray-700">Cancel</a>
          <button
            type="submit"
            :disabled="loading"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2"
          >
            <i class="fa fa-save"></i>
            <span v-if="!loading">Update Student</span>
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
    student: Object,
    departments: Array,
  },
  data() {
    return {
      loading: false,
      snackbar: useSnackbar(),
      form: useForm({
        department_id: this.student.department_id,
        regnum: this.student.regnum,
        firstnames: this.student.firstnames,
        surname: this.student.surname,
        gender: this.student.gender,
        email: this.student.user?.email || '',
        password: '',
      }),
    };
  },
  methods: {
    submit() {
      this.loading = true;
      this.form.put(`/admin/students/${this.student.id}`, {
        onSuccess: () => {
          this.snackbar.add({
            type: 'success',
            text: 'Student updated successfully!',
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


