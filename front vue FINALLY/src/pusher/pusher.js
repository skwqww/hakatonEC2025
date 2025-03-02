import Pusher from 'pusher-js'

Pusher.logToConsole = true;

export const pusher = new Pusher('1f5cc0b03f04832c0e68', {
  cluster: 'eu',
  encrypted: true,
});
