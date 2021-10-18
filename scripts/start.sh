#!/usr/bin/bash

echo "Starting React frontend.."
kitty --title "Frontend" ./spawnReact.sh&
echo "React frontend started!"

echo "Starting PHP backend.."
kitty --title "Backend" ./spawnPHP.sh&
echo "PHP backend started!"

echo "Website started!"

wait
