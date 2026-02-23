En la carpeta del proyecto creé un archivo llamado Dockerfile con el siguiente contenido:
FROM php:8.2-apache
COPY . /var/www/html/
EXPOSE 80

Con esta configuración se utiliza la imagen oficial de PHP 8.2 con Apache como base, se copian los archivos del proyecto al directorio público del servidor web dentro del contenedor y se expone el puerto 80 para permitir el acceso HTTP.

Abrí la terminal PowerShell.
Accedí a la carpeta donde estaba mi proyecto: cd C:\wamp64\www\investigacion

Ejecuté los siguientes comandos:
docker build -t investigacion-php .
Este comando construye una imagen Docker a partir del Dockerfile y la etiqueta con el nombre investigacion-php.

docker images
Permite verificar que la imagen fue creada correctamente y aparece en el repositorio local de Docker.

docker run -p 8080:80 investigacion-php
Este comando crea y ejecuta un contenedor a partir de la imagen, mapeando el puerto 8080 del host al puerto 80 del contenedor, permitiendo acceder a la aplicación desde el navegador mediante http://localhost:8080
.

Al abrir el navegador en localhost, el proyecto se mostraba correctamente, confirmando que el contenedor funcionaba adecuadamente.

Verifiqué el contexto actual de Kubernetes con el comando:
kubectl config current-context
Este comando permite confirmar que kubectl está apuntando al clúster correcto (en este caso, el clúster local de Docker Desktop).

En la carpeta del proyecto creé el archivo deployment.yaml con la siguiente configuración:
apiVersion: apps/v1
kind: Deployment
metadata:
name: php-app
spec:
replicas: 2
selector:
matchLabels:
app: php-app
template:
metadata:
labels:
app: php-app
spec:
containers:
- name: php-container
image: investigacion-php
imagePullPolicy: Never
ports:
- containerPort: 80

Este Deployment define la aplicación en Kubernetes, especificando 2 réplicas (dos pods) para garantizar alta disponibilidad. Se utiliza la imagen local investigacion-php y la política imagePullPolicy: Never indica que Kubernetes debe usar la imagen local sin intentar descargarla de un repositorio externo.

Ejecuté:
kubectl apply -f deployment.yaml
Este comando crea el Deployment en el clúster.

kubectl get pods
Permite verificar que los pods fueron creados y están en estado Running.

Luego, en la carpeta del proyecto creé el archivo service.yaml con el siguiente contenido:
apiVersion: v1
kind: Service
metadata:
name: php-service
spec:
type: LoadBalancer
selector:
app: php-app
ports:

port: 80
targetPort: 80

Este Service expone los pods del Deployment mediante un LoadBalancer. El selector app: php-app vincula el servicio con los pods correspondientes, y el puerto 80 permite enrutar el tráfico HTTP hacia los contenedores.

Ejecuté:
kubectl apply -f service.yaml
Este comando crea el servicio en el clúster.

kubectl get services
Permite verificar que el servicio fue creado correctamente y que el LoadBalancer está asignado.

Al acceder nuevamente a la aplicación mediante localhost, el tráfico HTTP es gestionado por el Service, que distribuye las solicitudes entre las réplicas disponibles, implementando balanceo de carga.

kubectl get pods -o wide
Este comando muestra información detallada de los pods, incluyendo IP interna y nodo donde se ejecutan, lo que permite validar la distribución de las réplicas.

Para configurar el escalado horizontal automático ejecuté:

kubectl top pods
Permite visualizar el consumo de CPU y memoria de los pods, información necesaria para definir reglas de escalado basadas en uso de recursos.

kubectl autoscale deployment php-app --cpu-percent=50 --min=2 --max=5
Este comando crea un Horizontal Pod Autoscaler (HPA) que ajusta automáticamente el número de réplicas del Deployment php-app. Si el uso promedio de CPU supera el 50%, Kubernetes incrementará las réplicas hasta un máximo de 5; si disminuye, reducirá las réplicas, manteniendo como mínimo 2.

kubectl get hpa
Permite verificar que el autoscaler fue creado correctamente y monitorear su estado y métricas de escalado.
