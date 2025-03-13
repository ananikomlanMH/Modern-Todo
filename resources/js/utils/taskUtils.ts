export const getStatusBadgeVariant = (status: string) => {
  switch (status) {
    case 'completed':
      return 'success';
    case 'in_progress':
      return 'warning';
    case 'cancelled':
      return 'destructive';
    case 'pending':
    default:
      return 'secondary';
  }
};

export const getPriorityBadgeVariant = (priority: string) => {
  switch (priority) {
    case 'urgent':
      return 'destructive';
    case 'high':
      return 'warning';
    case 'medium':
      return 'secondary';
    case 'low':
    default:
      return 'outline';
  }
};

export const getStatusLabel = (status: string) => {
  switch (status) {
    case 'completed':
      return 'Terminé';
    case 'in_progress':
      return 'En cours';
    case 'cancelled':
      return 'Annulé';
    case 'pending':
      return 'En attente';
    default:
      return status;
  }
};

export const getPriorityLabel = (priority: string) => {
  switch (priority) {
    case 'urgent':
      return 'Urgent';
    case 'high':
      return 'Haute';
    case 'medium':
      return 'Moyenne';
    case 'low':
      return 'Basse';
    default:
      return priority;
  }
}; 