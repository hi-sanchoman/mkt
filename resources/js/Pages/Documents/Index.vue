<template>
  <div class="w-full">
    <!-- Display documents in a table -->
    <div class="flex gap-3 items-center mb-2">
        <h2 class="text-2xl font-bold">Документы</h2>
        <button v-if="userIs([DIRECTOR])" @click="$modal.show('create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded hidden sm:block">Создать документ</button>
    </div>
    <table class="w-full whitespace-nowrap bg-white overflow-auto">
      <thead>
        <tr>
          <th class="border px-2 py-2 text-left">Название</th>
          <th class="border px-2 py-2 text-left">Описание</th>
          <th class="border px-2 py-2 text-left w-20" >Файл</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="document in documents" :key="document.id">
          <td class="border px-2 py-2">{{ document.name }}</td>
          <td class="border px-2 py-2">{{ document.desc }}</td>
          <td class="border">
            <div class="flex gap-1">
                <a :href="document.src" target="_blank" download="true" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-4 text-sm rounded">Скачать</a>
                <button v-if="userIs([DIRECTOR])"class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-4 text-sm rounded" @click="deleteDoc(document.id)">
                    Удалить
                </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Form for uploading documents -->    
    <modal name="create">
        <div class="p-4">
            <h2 class="mb-4 text-xl font-bold">Создать документ</h2>
            <form @submit.prevent="submitForm" enctype="multipart/form-data">
                <div class="mt-2">
                    <label for="name">Название</label>
                    <input v-model="documentForm.name" type="text" id="name" required class="w-full border py-0.5"/>
                </div>
                <div class="mt-2">
                    <label for="desc">Описание</label>
                    <input v-model="documentForm.desc" type="text" id="desc" class="w-full border py-0.5"/>
                </div>
                <div class="mt-2">
                    <label for="file">Выбрать файл</label>
                    <input @change="onFileChange" type="file" id="file" required class="w-full border py-0.5"/>
                </div>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded hidden sm:block mt-2" type="submit">
                    Загрузить документ
                </button>
            </form>
        </div>
    </modal>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import axios from 'axios'

export default {
  metaInfo: {
    title: 'Документы'
  },
  layout: Layout,
  data() {
    return {
      documentForm: {
        name: '',
        desc: '',
        file: null
      },
      documents: [] // List of documents fetched from the server
    };
  },
  methods: {
    // Handles file selection
    onFileChange(event) {
      this.documentForm.file = event.target.files[0];
    },
    
    // Submits the form to the backend
    submitForm() {
      const formData = new FormData();
      formData.append('name', this.documentForm.name);
      formData.append('desc', this.documentForm.desc);
      formData.append('file', this.documentForm.file);

      // Send data to the backend (replace with your API endpoint)
      axios.post('/api/documents', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then(response => {
        this.documents.push(response.data.document); // Add new document to the list
        this.$modal.hide('create');
        this.resetForm(); // Reset form fields
        this.fetchDocuments()
      })
      .catch(error => {
        console.error('Error uploading document:', error);
      });
    },

    // Fetches the list of documents
    fetchDocuments() {
      axios.get('/api/documents')
        .then(response => {
          this.documents = response.data.documents;
        })
        .catch(error => {
          console.error('Error fetching documents:', error);
          alert(error)
        });
    },

    deleteDoc(id) {

        if (!confirm('Вы уверены, что хотите удалить документ?')) {
            return;
        }

        axios.delete(`/api/documents/${id}`)
        .then(response => {
            this.fetchDocuments()
        })
        .catch(error => {
            console.error('Error uploading document:', error);
            alert(error)
        });
    },

    


    // Resets the form fields after successful submission
    resetForm() {
      this.documentForm.name = '';
      this.documentForm.desc = '';
      this.documentForm.file = null;
    }
  },

  // Fetch documents when the component is mounted
  mounted() {
    this.fetchDocuments();
  }
};
</script>