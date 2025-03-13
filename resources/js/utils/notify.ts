import Notify from 'simple-notify';
import 'simple-notify/dist/simple-notify.css';

type NotifyType = 'success' | 'error' | 'warning' | 'info';

export const notify = (title: string, text: string, type: NotifyType = 'success') => {
  new Notify({
    status: type,
    text: text,
    effect: 'fade',
    speed: 300,
    customClass: '',
    customIcon: '',
    showIcon: true,
    showCloseButton: true,
    autoclose: true,
    autotimeout: 3000,
    gap: 20,
    distance: 20,
      type: 1,
      position: 'x-center bottom',
  });
};
