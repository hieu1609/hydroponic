import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DevicesManagementComponent } from './devices-management.component';

describe('DevicesManagementComponent', () => {
  let component: DevicesManagementComponent;
  let fixture: ComponentFixture<DevicesManagementComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DevicesManagementComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DevicesManagementComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
