<template>
    <h3 class="mb-3">Dokumente</h3>
    <div class="grid sm:grid-cols-8 grid-cols-6 gap-3">
        <template v-for="document in documents" :key="document.id">
            <document-item @delete="deleteDocument" :document="document" />
        </template>
        <document-upload v-if="show_upload" :id="id" :documents="documents" />
    </div>
</template>

<script>
import { useForm } from '@inertiajs/inertia-vue3';
import DocumentItem from '@/Components/Documents/Item.vue';
import DocumentUpload from '@/Components/Documents/Upload.vue';

export default {
  components: {
    DocumentItem,
    DocumentUpload,
  },
  props: {
    initial_documents: Object,
    id: Number,
    show_upload: Boolean,
  },
  data() {
    return {
      documents: this.initial_documents,
    };
  },
  methods: {
    deleteDocument(documentId) {
      const form = useForm(`deleteDocument${documentId}`, { id: documentId });
      form.delete(route('documents.destroy', this.id), {
        preserveScroll: true,
        onSuccess: () => {
          form.reset();
          this.documents = this.initial_documents;
        },
      });
    },
  },
};
</script>
