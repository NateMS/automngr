<template>
  <div>  
      <small-title title="Dokumente" class="mb-3" />
      <div class="grid md:grid-cols-6 sm:grid-cols-4 grid-cols-2 gap-3">
          <template v-for="document in documents" :key="document.id">
              <document-item @delete="deleteDocument" :document="document" />
          </template>
          <document-upload v-if="show_upload" :id="id" :documentable_type="documentable_type" :documents="documents" />
      </div>
  </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import DocumentItem from './Item.vue';
import DocumentUpload from './Upload.vue';
import SmallTitle from './../SmallTitle.vue';

export default {
  components: {
    DocumentItem,
    DocumentUpload,
    SmallTitle,
  },
  props: {
    initial_documents: Object,
    id: Number,
    documentable_type: String,
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
