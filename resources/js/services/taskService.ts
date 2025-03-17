import { router } from '@inertiajs/vue3';
import { notify } from '@/utils/notify';
import { parseDate, type DateValue } from '@internationalized/date';
import { TaskForm } from '@/types';

export class TaskService {
  static formatDateForApi(date: DateValue | null): string | null {
    if (!date) return null;
    return date.toString();
  }

  static parseDateSafely(dateString: string | null): DateValue | null {
    if (!dateString) return null;
    try {
      const datePart = dateString.split('T')[0];
      return parseDate(datePart);
    } catch (e) {
      console.error('Error parsing date:', e);
      return null;
    }
  }

  static validateForm(form: TaskForm): boolean {
    const errors: string[] = [];

    if (!form.title) errors.push('Le titre est requis');
    if (!form.status) errors.push('Le statut est requis');
    if (!form.priority) errors.push('La priorité est requise');

    if (errors.length > 0) {
      notify('Erreur de validation', errors.join('\n'), 'error');
      return false;
    }

    return true;
  }

  static async createTask(form: TaskForm): Promise<void> {
    if (!this.validateForm(form)) return;

    const formData = {
      ...form,
      due_date: this.formatDateForApi(form.due_date)
    };

    router.post(route('tasks.store'), formData, {
      onSuccess: () => {
        notify(
          'Tâche créée',
          'La nouvelle tâche a été créée avec succès',
          'success'
        );
      },
      onError: () => {
        notify(
          'Erreur de validation',
          'Veuillez vérifier les champs du formulaire',
          'error'
        );
      }
    });
  }

  static async updateTask(taskId: number, form: TaskForm): Promise<void> {
    if (!this.validateForm(form)) return;

    const formData = {
      ...form,
      due_date: this.formatDateForApi(form.due_date)
    };

    router.put(route('tasks.update', taskId), formData, {
      onSuccess: () => {
        notify(
          'Tâche modifiée',
          'La tâche a été mise à jour avec succès',
          'success'
        );
      },
      onError: () => {
        notify(
          'Erreur de validation',
          'Veuillez vérifier les champs du formulaire',
          'error'
        );
      }
    });
  }

  static async deleteTask(taskId: number): Promise<void> {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) return;

    router.delete(route('tasks.destroy', taskId), {
      onSuccess: () => {
        notify(
          'Tâche supprimée',
          'La tâche a été supprimée avec succès',
          'success'
        );
      },
      onError: () => {
        notify(
          'Erreur',
          'Une erreur est survenue lors de la suppression',
          'error'
        );
      }
    });
  }
}
