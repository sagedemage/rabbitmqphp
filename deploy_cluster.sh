#!/bin/bash

# Define VM names and settings
BASE_VM="BaseVM"
VM_FRONTEND="FrontEndNode"
VM_BACKEND="BackEndNode"
VM_DMZ="DMZNode"
NATNETWORK_NAME="NatNetwork"
INTERNAL_NETWORK_NAME="IntNet"

# Check if VirtualBox is installed
if ! command -v VBoxManage &> /dev/null; then
    echo "VirtualBox is not installed. Please install it first."
    exit 1
fi

# Create NAT Network (if not exists)
VBoxManage list natnetworks | grep -q "$NATNETWORK_NAME" || VBoxManage natnetwork add --netname "$NATNETWORK_NAME" --enable --dhcp on

# Function to clone and configure VMs
clone_and_setup_vm() {
    local vm_name="$1"
    local network_type="$2"

    # Clone the base VM
    VBoxManage clonevm "$BASE_VM" --name "$vm_name" --register

    # Modify VM settings as needed
    # VBoxManage modifyvm "$vm_name" --memory 2048 --cpus 2

    # Setup network
    if [ "$network_type" == "dmz" ]; then
        # DMZ Node - Internal Network only
        VBoxManage modifyvm "$vm_name" --nic1 intnet --intnet1 "$INTERNAL_NETWORK_NAME"
    else
        # Front-end and Back-end Nodes - NAT Network and Internal Network
        VBoxManage modifyvm "$vm_name" --nic1 natnetwork --nat-network1 "$NATNETWORK_NAME"
        VBoxManage modifyvm "$vm_name" --nic2 intnet --intnet2 "$INTERNAL_NETWORK_NAME"
    fi

    # Start VM
    VBoxManage startvm "$vm_name" --type headless

    # Wait for VM to start (customize as needed)
    # sleep 30

    # Additional configuration commands can go here
}

# Deploy Front-end Node
clone_and_setup_vm "$VM_FRONTEND" "external" &

# Deploy Back-end Node
clone_and_setup_vm "$VM_BACKEND" "external" &

# Deploy DMZ Node
clone_and_setup_vm "$VM_DMZ" "dmz" &

wait
echo "Cluster deployment completed."
