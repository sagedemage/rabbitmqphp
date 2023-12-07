#!/bin/bash

# Define VM names and settings
BASE_VM="BaseVM"
VM_FRONTEND="FrontEndNode"
VM_BACKEND="BackEndNode"
VM_DMZ="DMZNode"
HOSTONLY_IF="vboxnet0"
HOSTONLY_IP_BASE="192.168.56."

# Check if VirtualBox is installed
if ! command -v VBoxManage &> /dev/null; then
    echo "VirtualBox is not installed. Please install it first."
    exit 1
fi

# Create host-only network (if not exists)
VBoxManage list hostonlyifs | grep -q "$HOSTONLY_IF" || VBoxManage hostonlyif create
VBoxManage hostonlyif ipconfig "$HOSTONLY_IF" --ip "${HOSTONLY_IP_BASE}1"

# Function to clone and configure VMs
clone_and_setup_vm() {
    local vm_name="$1"
    local vm_ip="${HOSTONLY_IP_BASE}$2"

    # Clone the base VM
    VBoxManage clonevm "$BASE_VM" --name "$vm_name" --register

    # Modify VM settings as needed
    # VBoxManage modifyvm "$vm_name" --memory 2048 --cpus 2

    # Setup network
    VBoxManage modifyvm "$vm_name" --hostonlyadapter1 "$HOSTONLY_IF"
    VBoxManage modifyvm "$vm_name" --nic1 hostonly

    # Start VM
    VBoxManage startvm "$vm_name" --type headless

    # Wait for VM to start (customize as needed)
    # sleep 30

    # Additional configuration commands can go here
}

# Deploy Front-end Node
clone_and_setup_vm "$VM_FRONTEND" "101" &

# Deploy Back-end Node
clone_and_setup_vm "$VM_BACKEND" "102" &

# Deploy DMZ Node
clone_and_setup_vm "$VM_DMZ" "103" &

wait
echo "Cluster deployment completed."
