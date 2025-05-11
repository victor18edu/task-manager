#!/bin/bash

echo "Adicionando alias 'sail' ao seu shell..."

if [[ $SHELL == *"zsh" ]]; then
    FILE="$HOME/.zshrc"
elif [[ $SHELL == *"bash" ]]; then
    FILE="$HOME/.bashrc"
else
    echo "Shell não reconhecido. Adicione manualmente: alias sail='bash vendor/bin/sail'"
    exit 1
fi

if grep -q "alias sail=" "$FILE"; then
    echo "Alias 'sail' já existe em $FILE"
else
    echo "alias sail='bash vendor/bin/sail'" >> "$FILE"
    echo "Alias adicionada ao $FILE. Reinicie o terminal ou execute: source $FILE"
fi
